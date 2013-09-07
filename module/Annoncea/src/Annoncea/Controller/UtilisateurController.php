<?php
namespace Annoncea\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Annoncea\Form\InscriptionForm;
use Annoncea\Form\InscriptionFormValidator;
use Annoncea\Form\ConnexionForm;
use Annoncea\Form\ConnexionFormValidator;
use Annoncea\Model\Utilisateur;
use Annoncea\Model\BaseAnnoncea as BDD;

class UtilisateurController extends AbstractActionController
{
    public function indexAction(){
        if(true){ //a remplacer par un test si l'utilisateur n'est pas connecté
            return $this->redirect()->toRoute('utilisateur', array(
                'action' => 'connexion'
            ));
        }    
    }
    
    public function connexionAction()
    {
        $form = new ConnexionForm();
        $form->get('submit')->setValue('Connexion');
        $authAdapter = $this->serviceLocator->get('AuthAdapter');

        $request = $this->getRequest();//récupère la requete pour voir si c'est la 1ere fois ou pas qu'on vient sur la page
        if ($request->isPost()) {//si c'est pas la 1ere fois
            $connexionFormValidator = new ConnexionFormValidator();
            $form->setInputFilter($connexionFormValidator->getInputFilter());
            $form->setData($request->getPost()); //on récupère ce qu'il y a dans la requete et on le met dans le formulaire
            
            if ($form->isValid()) { //si il passe le validateur 
                       
              $authAdapter->setIdentity($form->get('mail')->getValue());
              $authAdapter->setCredential($form->get('mdp')->getValue());
              return $this->redirect()->toRoute('home');
            }
        }
        return array('form' => $form); //passé à ce qui crée la vue 
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
                       
              
                $utilisateur = new Utilisateur();
                $utilisateur->exchangeArray($form->getData()); //remplit l'objet à partir d'un tableau qu'on récupère du formulaire 
                BDD::getUtilisateurTable($this->serviceLocator)->saveUtilisateur($utilisateur);

                return $this->redirect()->toRoute('home');
            }
        }
        return array('form' => $form);
    }
        
    
}