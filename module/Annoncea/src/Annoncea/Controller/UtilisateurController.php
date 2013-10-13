<?php
namespace Annoncea\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Annoncea\Form\InscriptionForm;
use Annoncea\Form\InscriptionFormValidator;
use Annoncea\Form\ConnexionForm;
use Annoncea\Form\ConnexionFormValidator;
use Annoncea\Form\MessageForm;
use Annoncea\Form\MessageFormValidator;
use Annoncea\Model\Utilisateur;
use Annoncea\Model\Annonce;
use Annoncea\Model\BaseAnnoncea as BDD;
use Zend\Authentication\AuthenticationService;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Crypt\Password\Bcrypt;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Message as MailMessage;

class UtilisateurController extends AbstractActionController
{
    
    
   public function indexAction(){
        $auth = new AuthenticationService();
        if(!$auth->hasIdentity()){ //si l'utilisateur n'est pas connecté
            return $this->redirect()->toRoute('utilisateur', array(
                'action' => 'connexion'
            ));
        }
        return array('auth' => $auth);    
    }
    
    public function connexionAction()
    {
        //instancie le service authentification 
        $auth = new AuthenticationService();
        
        //test si déjà connecté
        if($auth->hasIdentity()) {
            return $this->redirect()->toRoute('home');
        }
        
        $form = new ConnexionForm();
        
        $retour = array('form' => $form);
        
        $form->get('submit')->setValue('Connexion');
        //adaptateur d'authentification (sert uniquement à la connexion)
        $authAdapter = $this->serviceLocator->get('AuthAdapter');

        $request = $this->getRequest();//récupère la requete pour voir si c'est la 1ere fois ou pas qu'on vient sur la page
        if ($request->isPost()) {//si c'est pas la 1ere fois
            $connexionFormValidator = new ConnexionFormValidator();
            $form->setInputFilter($connexionFormValidator->getInputFilter());
            $form->setData($request->getPost()); //on récupère ce qu'il y a dans la requete et on le met dans le formulaire
            
            if ($form->isValid()) { //si il passe le validateur (verif que c'est bien un email etc...)
                       
                $authAdapter->setIdentity($form->get('mail')->getValue());

                $email = $form->get('mail')->getValue(); //récupère l'adresse mail
                $passw = $form->get('mdp')->getValue(); //récupère le mot de passe


                $db = mysql_connect('localhost', 'root', ''); //connexion à la base
                mysql_select_db('annoncea',$db);
                $sql =  'SELECT mdp FROM utilisateur where mail="'.$email.'";';

                $req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());

                while($data = mysql_fetch_assoc($req))
                {
                    $resultat = $data['mdp'];
                    break;
                } 

                $securePass = $resultat; //A CORRIGER !!!!!!!!!!!!

                $bcrypt = new Bcrypt();

                if ($bcrypt->verify($passw, $securePass)) 
                {

                    $authAdapter->setCredential($resultat);

                    $result = $auth->authenticate($authAdapter); //verif si les données sont correctes

                    if ($result->isValid()) {
                        return $this->redirect()->toRoute('home');
                    } else {
                        $retour['erreur'] = 'Email ou mot de passe incorrect';
                    }

                } else {
                    echo 'Cryptage non réussi';    
                }
              
            
            }
        }
        return $retour; //passé à ce qui crée la vue 

    }

    public function deconnexionAction(){
        $auth = new AuthenticationService();
        $auth->clearIdentity();
    } 

    
    public function inscriptionAction()
    {
        $form = new InscriptionForm();
        $form->get('rang')->setValue('membre');
        
                       
        $form->get('id_dept')->setValueOptions(BDD::getSelecteurDepartement($this->serviceLocator));
        $form->get('submit')->setValue('Inscription'); //change le bouton "submit" en "inscription"
        
        $form->get('captcha')->getCaptcha()->setOptions(array(
            'imgUrl'=> $this->getRequest()->getBasePath() . "/img/captcha",
            'imgDir'=> $_SERVER['CONTEXT_DOCUMENT_ROOT'] . $this->getRequest()->getBasePath() . "/img/captcha/",
            'font'=> $_SERVER['CONTEXT_DOCUMENT_ROOT'] . $this->getRequest()->getBasePath() . "/fonts/arial.ttf",
        ));

        $request = $this->getRequest();//récupère la requete pour voir si c'est la 1ere fois ou pas qu'on vient sur la page
        if ($request->isPost()) {//si c'est pas la 1ere fois
            $inscriptionFormValidator = new InscriptionFormValidator();
            $inscriptionFormValidator->setDbAdapter($this->serviceLocator->get('Zend\Db\Adapter\Adapter'));//lien avec db qui sert pour le validateur qui vérifie que l'email (entrée) n'est pas déjà dans la table
            $form->setInputFilter($inscriptionFormValidator->getInputFilter());
            $form->setData($request->getPost()); //on récupère ce qu'il y a dans la requete et on le met dans le formulaire
            
            if ($form->isValid()) { //si il passe le validateur 
                       
                //création de l'utilisateur
                $utilisateur = new Utilisateur();
                $utilisateur->exchangeArray($form->getData()); //remplit l'objet à partir d'un tableau qu'on récupère du formulaire 
                
                $email = $form->get('mail')->getValue();
                $sujet = 'Bienvenue sur le site Annoncea';
                $corps = '<!doctype html>
							<html lang="fr">
								<head>
									<meta charset="utf-8">
									<style media="screen">
										html {
											height: 100%;
											background: #bfd255; /* Old browsers */
											background: -moz-linear-gradient(-45deg,  #bfd255 0%, #8eb92a 50%, #72aa00 51%, #9ecb2d 100%); /* FF3.6+ */
											background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,#bfd255), color-stop(50%,#8eb92a), color-stop(51%,#72aa00), color-stop(100%,#9ecb2d)); /* Chrome,Safari4+ */
											background: -webkit-linear-gradient(-45deg,  #bfd255 0%,#8eb92a 50%,#72aa00 51%,#9ecb2d 100%); /* Chrome10+,Safari5.1+ */
											background: -o-linear-gradient(-45deg,  #bfd255 0%,#8eb92a 50%,#72aa00 51%,#9ecb2d 100%); /* Opera 11.10+ */
											background: -ms-linear-gradient(-45deg,  #bfd255 0%,#8eb92a 50%,#72aa00 51%,#9ecb2d 100%); /* IE10+ */
											background: linear-gradient(135deg,  #bfd255 0%,#8eb92a 50%,#72aa00 51%,#9ecb2d 100%); /* W3C */
											filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#bfd255\', endColorstr=\'#9ecb2d\',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
										}
										body {
											height: 100%;
											margin: 0;
											padding: 0;
										}
										#page-table {
											height: 100%;
											width: 100%;
											border-collapse: collapse;
											text-align: center;
										}
										#page-td {
											height: 100%;
											padding: 0;
											vertical-align: middle;
										}
										div#global {
											width: 500px;
											margin: 20px auto;
											text-align: left;
										}
										div#global {
											padding: 10px 20px;
											border: 1px solid black;
											font-family: Arial, Helvetica, sans-serif;
											margin: auto;
											box-shadow: 1px 1px 12px #aaa;
											border-radius: 4px;
											background: #e9eaee;
											border: 2px solid #ffffff;
											text-align: center;
											box-shadow: 1px 2px 6px rgba(0,0,0, 0.5);
											-moz-box-shadow: 1px 2px 6px rgba(0,0,0, 0.5);
											-webkit-box-shadow: 1px 2px 6px rgba(0,0,0, 0.5);
										}

										a {
											text-decoration: none
										}

										.myButton {

											-moz-box-shadow: inset 0px 1px 0px 0px #a4e271;
											-webkit-box-shadow: inset 0px 1px 0px 0px #a4e271;
											box-shadow: inset 0px 1px 0px 0px #a4e271;
											background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #89c403), color-stop(1, #77a809));
											background: -moz-linear-gradient(top, #89c403 5%, #77a809 100%);
											background: -webkit-linear-gradient(top, #89c403 5%, #77a809 100%);
											background: -o-linear-gradient(top, #89c403 5%, #77a809 100%);
											background: -ms-linear-gradient(top, #89c403 5%, #77a809 100%);
											background: linear-gradient(to bottom, #89c403 5%, #77a809 100%);
											filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#89c403\', endColorstr=\'#77a809\',GradientType=0);
											background-color: #89c403;
											-moz-border-radius: 5px;
											-webkit-border-radius: 5px;
											border-radius: 5px;
											border: 3px solid #74b807;
											display: inline-block;
											color: #696663;
											font-family: arial;
											font-size: 15px;
											font-weight: bold;
											padding: 11px 24px;
											text-decoration: none;
											text-shadow: 0px 1px 0px #528009;
										}
										.myButton:hover {

											background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #77a809), color-stop(1, #89c403));
											background: -moz-linear-gradient(top, #77a809 5%, #89c403 100%);
											background: -webkit-linear-gradient(top, #77a809 5%, #89c403 100%);
											background: -o-linear-gradient(top, #77a809 5%, #89c403 100%);
											background: -ms-linear-gradient(top, #77a809 5%, #89c403 100%);
											background: linear-gradient(to bottom, #77a809 5%, #89c403 100%);
											filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#77a809\', endColorstr=\'#89c403\',GradientType=0);
											background-color: #77a809;
										}
										.myButton:active {
											position: relative;
											top: 1px;
										}

									</style>
								</head>

								<body>
									<table id="page-table">
										<tr>
											<td id="page-td">
											<div id="global">
												<img src="./banniere.png" />

												<div>
													<p>
														Bonjour "fonction nom/prenom",
													</p>

													<p>
														Vous venez de vous inscrire sur notre site "Annoncea" et nous vous en remercions.
													</p>

													<p>
														Nous espérons que vous puissiez trouver votre bonheur parmit les diverses annonces présentes sur notre site et que vous dénichiez de la perle rare.
													</p>

													<p>
														Merci de votre confiance et à bientôt sur "Annoncea".
													</p>

													<p>
														L\'équipe d\'Annoncea.
													</p>

													<a href="#" class="myButton">Acceder à Annoncea</a>
												</div>
											</div><!--#global--></td>
										</tr>
									</table><!--#page-table-->

								</body>
							</html>
							';
                
                $this->sendMessage($email, $sujet, $corps);
                
                $motDePasse = $form->get('mdp')->getValue();

                $bcrypt = new Bcrypt();
                $securePass = $bcrypt->create($motDePasse);

                $utilisateur->mdp = $securePass;

                
                BDD::getUtilisateurTable($this->serviceLocator)->saveUtilisateur($utilisateur);
                $auth = new AuthenticationService();
                //adaptateur d'authentification (sert uniquement à la connexion)
                $authAdapter = $this->serviceLocator->get('AuthAdapter');
                
                $authAdapter->setIdentity($form->get('mail')->getValue());
                //$authAdapter->setCredential($form->get('mdp')->getValue());
                
                $passw = $form->get('mdp')->getValue();


                if ($bcrypt->verify($passw, $securePass)) 
                {
                    $db = mysql_connect('localhost', 'root', ''); //connexion à la base 
                    mysql_select_db('annoncea',$db);
                    $sql =  'SELECT mdp FROM utilisateur where mail="'.$email.'";';
                    echo $sql;

                    $req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 

                    while($data = mysql_fetch_assoc($req))
                    {
                        $resultat = $data['mdp'];
                        echo $resultat;
                        break;
                    } 

                    $authAdapter->setCredential($resultat);

                    $result = $auth->authenticate($authAdapter); //verif si les données sont correctes


                } else {
                    echo 'Cryptage Non Réussi.';    
                }
                return $this->redirect()->toRoute('home');
            }
        }
        return array('form' => $form);
    }
    
    /*public function inscriptionAction()
    {
        $form = new InscriptionForm();
        $form->get('rang')->setValue('membre');
        
                       
        $form->get('id_dept')->setValueOptions(BDD::getSelecteurDepartement($this->serviceLocator));
        $form->get('submit')->setValue('Inscription'); //change le bouton "submit" en "inscription"
        
        $form->get('captcha')->getCaptcha()->setOptions(array(
            'imgUrl'=> $this->getRequest()->getBasePath() . "/img/captcha",
            'imgDir'=> $_SERVER['CONTEXT_DOCUMENT_ROOT'] . $this->getRequest()->getBasePath() . "/img/captcha/",
            'font'=> $_SERVER['CONTEXT_DOCUMENT_ROOT'] . $this->getRequest()->getBasePath() . "/fonts/arial.ttf",
        ));

        $request = $this->getRequest();//récupère la requete pour voir si c'est la 1ere fois ou pas qu'on vient sur la page
        if ($request->isPost()) {//si c'est pas la 1ere fois
            $inscriptionFormValidator = new InscriptionFormValidator();
            $inscriptionFormValidator->setDbAdapter($this->serviceLocator->get('Zend\Db\Adapter\Adapter'));//lien avec db qui sert pour le validateur qui vérifie que l'email (entrée) n'est pas déjà dans la table
            $form->setInputFilter($inscriptionFormValidator->getInputFilter());
            $form->setData($request->getPost()); //on récupère ce qu'il y a dans la requete et on le met dans le formulaire
            
            if ($form->isValid()) { //si il passe le validateur 
                       
                //création de l'utilisateur
                $utilisateur = new Utilisateur();
                $utilisateur->exchangeArray($form->getData()); //remplit l'objet à partir d'un tableau qu'on récupère du formulaire 
                BDD::getUtilisateurTable($this->serviceLocator)->saveUtilisateur($utilisateur);

                //connexion de l'utilisateur
                //instancie le service authentification 
                $auth = new AuthenticationService();
                //adaptateur d'authentification (sert uniquement à la connexion)
                $authAdapter = $this->serviceLocator->get('AuthAdapter');
                
                $authAdapter->setIdentity($form->get('mail')->getValue());
                $authAdapter->setCredential($form->get('mdp')->getValue());
              
                $result = $auth->authenticate($authAdapter); //verif si les données sont correctes
              

                return $this->redirect()->toRoute('home');
            }
        }
        return array('form' => $form);
    } */ 
    
   /*public function connexionAction()
    {
        //instancie le service authentification 
        $auth = new AuthenticationService();
        
        //test si déjà connecté
        if($auth->hasIdentity()) {
            return $this->redirect()->toRoute('home');
        }
        
        $form = new ConnexionForm();
        
        $retour = array('form' => $form);
        
        $form->get('submit')->setValue('Connexion');
        //adaptateur d'authentification (sert uniquement à la connexion)
        $authAdapter = $this->serviceLocator->get('AuthAdapter');

        $request = $this->getRequest();//récupère la requete pour voir si c'est la 1ere fois ou pas qu'on vient sur la page
        if ($request->isPost()) {//si c'est pas la 1ere fois
            $connexionFormValidator = new ConnexionFormValidator();
            $form->setInputFilter($connexionFormValidator->getInputFilter());
            $form->setData($request->getPost()); //on récupère ce qu'il y a dans la requete et on le met dans le formulaire
            
            if ($form->isValid()) { //si il passe le validateur (verif que c'est bien un email etc...)
                       
              $authAdapter->setIdentity($form->get('mail')->getValue());
              $authAdapter->setCredential($form->get('mdp')->getValue());
                            
              $result = $auth->authenticate($authAdapter); //verif si les données sont correctes
              
            if ($result->isValid()) {
              return $this->redirect()->toRoute('annonce', array('action' => 'index'));
            } else {
                $retour['erreur'] = 'Email ou mot de passe incorrect';
            }
         }
        }
        return $retour; //passé à ce qui crée la vue 

    }*/


    /*public function deconnexionAction(){
        $auth = new AuthenticationService();
        $auth->clearIdentity();
    }*/


  
      //les petites affaires de l'utilisateurs 
          
       public function mesannoncesAction() {
           $auth = new AuthenticationService();
           if(!$auth->hasIdentity()){ //si l'utilisateur n'est pas connecté
               return $this->redirect()->toRoute('utilisateur', array(
                   'action' => 'connexion'
               ));
            }
            
            $retour['co'] = true; 
            
            $annonces = BDD::getAnnonceTable($this->serviceLocator)->getAnnonceAuteur($auth->getIdentity());
            $metaAnnonces = array();
            foreach($annonces as $annonce) {
                $metaAnnonces[$annonce->id_annonce] = array(
                    'photo'=> BDD::getPhotoTable($this->serviceLocator)->getByIdAnnonce($annonce->id_annonce)->current(),
                    'departement' => BDD::getDepartementTable($this->serviceLocator)->getDepartement($annonce->id_dept),
                    'categorie' => BDD::getCategorieTable($this->serviceLocator)->getCategorie($annonce->id_cat),
                );
            }
        
            $retour['annonces'] = BDD::getAnnonceTable($this->serviceLocator)->getAnnonceAuteur($auth->getIdentity(), true);
            $retour['meta'] = $metaAnnonces;   
            return $retour;
        }

  
   public function mesfavorisAction() {
        $auth = new AuthenticationService();
        if(!$auth->hasIdentity()){ //si l'utilisateur n'est pas connecté
            return $this->redirect()->toRoute('utilisateur', array(
                'action' => 'connexion'
            ));
        }
        
        $retour['co'] = true; 
        
         $favoris = BDD::getFavorisTable($this->serviceLocator)->getByMail($auth->getIdentity());
         $annonces = array();   
         foreach($favoris as $fav) {
             $id_annonce = $fav->id_annonce;
             $annonces[$id_annonce] = BDD::getAnnonceTable($this->serviceLocator)->getAnnonce($id_annonce);
             //$annonce = BDD::getAnnonceTable($this->serviceLocator)->getAnnonce($id_annonce);
        }
         $metaAnnonces = array();
         foreach($annonces as $annonce) {
                $metaAnnonces[$annonce->id_annonce] = array(
                    'photo'=> BDD::getPhotoTable($this->serviceLocator)->getByIdAnnonce($annonce->id_annonce)->current(),
                    'departement' => BDD::getDepartementTable($this->serviceLocator)->getDepartement($annonce->id_dept),
                    'categorie' => BDD::getCategorieTable($this->serviceLocator)->getCategorie($annonce->id_cat),
                );
          }
    
        $retour['annonces'] = $annonces;
        $retour['meta'] = $metaAnnonces;   
        return $retour;
    }

    public function messageAction() {
        
        $auth = new AuthenticationService();

        if(!$auth->hasIdentity())
        {
            return $this->redirect()->toRoute('utilisateur', array('action' => 'connexion'));
        }

        $retour['co'] = true;

        $form = new MessageForm();

        $form->get('titre');
        $form->get('contenu');
        $form->get('mail_auteur')->setValue($auth->getIdentity());
		$form->get('submit')->setValue('Envoi');
		
        $annonce = new Annonce();
		$annonce = BDD::getAnnonceTable($this->serviceLocator)->getAnnonce( (int) $this->params()->fromRoute('id', 0));
		
        $request = $this->getRequest();

        if($request->isPost())
        {

			$post = array_merge_recursive(
                $request->getPost()->toArray()
            );

            $MessageFormValidator = new MessageFormValidator(); 
            $form->setInputFilter($MessageFormValidator->getInputFilter());
            $form->setData($post);
            if ($form->isValid()) {

                $email = $annonce->mail_auteur;
				$titre = $form->get('titre')->getValue();
				$contenu = '<p> Un de nos Utilisateur souhaite prendre contact avec vous au sujet d\'une de vos annonces ('.$annonce->titre.'). Voici son message : </p></ br>
							<p>'.$form->get('contenu')->getValue().'</p>
							</ br>
							</ br>
							Veuillez reprendre contact avec lui via son adresse mail que voici : '.$auth->getIdentity();
				
                $this->sendMessage($email, $titre, $contenu);
			
				return $this->redirect()->toRoute('home');

            }
        }
    
    return array('form' => $form);
    }

    private function sendMessage($dest, $sujet, $corps){
        $transport = $this->serviceLocator->get('MailTransport');
        
        $html = new MimePart($corps);
        $html->type = "text/html";
        $body = new MimeMessage();
        $body->addPart($html);  
                    
        $message = new MailMessage();
        $message->addFrom('projetannoncea@gmail.com')
                ->setSubject($sujet)
                ->addTo($dest)
                ->setBody($body); 

        $transport->send($message);
    }
}