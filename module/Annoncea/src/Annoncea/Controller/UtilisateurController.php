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
    
        $retour['annonces'] = BDD::getAnnonceTable($this->serviceLocator)->getAnnonceAuteur($auth->getIdentity());
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
              $authAdapter->setCredential($form->get('mdp')->getValue());
              
              $result = $auth->authenticate($authAdapter); //verif si les données sont correctes
              
            if ($result->isValid()) {
              return $this->redirect()->toRoute('home');
            } else {
                $retour['erreur'] = 'Email ou mot de passe incorrect';
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
    }
        
    
}