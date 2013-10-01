<?php
namespace Annoncea\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Annoncea\Form\InscriptionForm;
use Annoncea\Form\InscriptionFormValidator;
use Annoncea\Form\ConnexionForm;
use Annoncea\Form\ConnexionFormValidator;
use Annoncea\Model\Utilisateur;
use Annoncea\Model\BaseAnnoncea as BDD;
use Zend\Authentication\AuthenticationService;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Crypt\Password\Bcrypt;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;

class UtilisateurController extends AbstractActionController
{
    
    
    public function voirAnnoncesAction() {
        $auth = new AuthenticationService();
        if(!$auth->hasIdentity()){ //si l'utilisateur n'est pas connecté
            return $this->redirect()->toRoute('utilisateur', array(
                'action' => 'connexion'
            ));
        }
        
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

                $securePass = $resultat;

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
                    echo 'fuck you';    
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

              $message = new Message();
                $message->addTo($email)
                 ->addFrom('projetannoncea@gmail.com')
                ->setSubject('Test send mail using ZF2');
    
                // Setup SMTP transport using LOGIN authentication
                $transport = new SmtpTransport();
                $options   = new SmtpOptions(array(
                 'host'              => 'smtp.gmail.com',
                    'connection_class'  => 'login',
                    'connection_config' => array(
                    'ssl'       => 'tls',
                    'username' => 'projetannoncea@gmail.com',
                    'password' => 'a1z2e3r4t5'
                ),
                'port' => 587,
                ));
     
                $html = new MimePart('<b>heii, <i>sorry</i>,Hi its annoncea team</b>');
                $html->type = "text/html";
     
                $body = new MimeMessage();
                $body->addPart($html);
     
                $message->setBody($body);
 
                $transport->setOptions($options);
                $transport->send($message);
                
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
                    echo 'fuck you';    
                }
                           

                return $this->redirect()->toRoute('home');
            }
        }
        return array('form' => $form);
    }

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

}