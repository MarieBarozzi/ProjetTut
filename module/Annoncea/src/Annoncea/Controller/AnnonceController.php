<?php
namespace Annoncea\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Annoncea\Model\Annonce;
use Annoncea\Form\AnnonceForm;
use Annoncea\Form\AnnonceFormValidator;
use Annoncea\Model\BaseAnnoncea as BDD;
use Zend\Authentication\AuthenticationService;

class AnnonceController extends AbstractActionController
{
	
    public function indexAction()
    {
        $auth = new AuthenticationService();
        $retour = array();
        if($auth->hasIdentity()) {
            $retour['co'] = 'true';
        }
        
        $retour['annonces'] = BDD::getAnnonceTable($this->serviceLocator)->fetchAll();
    	return $retour;
    }

    public function addAction()
    {
        //si utilisateur n'est pas connecté  
        $auth = new AuthenticationService();
        if(!$auth->hasIdentity()) {
             return $this->redirect()->toRoute('utilisateur', array(
                'action' => 'connexion' //ajouter un message lui disant qu'il doit etre co pour ajouter une annonce ? 
            ));
        }
         
               
        $form = new AnnonceForm();
        
        $form->get('id_dept')->setValueOptions(BDD::getSelecteurDepartement($this->serviceLocator));
        $form->get('id_cat')->setValueOptions(BDD::getSelecteurCategorie($this->serviceLocator));   
    
        $form->get('date_crea')->setValue(date('Y-m-d'));
        $form->get('date_modif')->setValue(date('Y-m-d'));
        $form->get('submit')->setValue('Ajout'); //change le bouton "submit" en "ajout"

        $request = $this->getRequest();//récupère la requete pour voir si c'est la 1ere fois ou pas qu'on vient sur la page
        if ($request->isPost()) {//si c'est pas la 1ere fois
            $annonceFormValidator = new AnnonceFormValidator();
            $form->setInputFilter($annonceFormValidator->getInputFilter());
            $form->setData($request->getPost()); //on récupère ce qu'il y a dans la requete et on le met dans le formulaire
            
            if ($form->isValid()) { //si il passe le validateur 
              
                $annonce = new Annonce();
                $annonce->exchangeArray($form->getData()); //remplit l'objet à partir d'un tableau qu'on récupère du formulaire 
                
                BDD::getAnnonceTable($this->serviceLocator)->saveAnnonce($annonce);
                
                // Redirect to list of albums
                return $this->redirect()->toRoute('annonce');
            }
        }
        return array('form' => $form);
    }
        
      

    public function editAction()
    {
        $id_annonce = (int) $this->params()->fromRoute('id', 0);
        if (!$id_annonce) {
            return $this->redirect()->toRoute('annonce', array(
                'action' => 'add'
            ));
        }
                
        // Get the Album with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $annonce = BDD::getAnnonceTable($this->serviceLocator)->getAnnonce($id_annonce);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('annonce', array(
                'action' => 'index'
            ));
        }
       
        
        $form  = new AnnonceForm();
        
        $form->get('id_dept')->setValueOptions(BDD::getSelecteurDepartement($this->serviceLocator));
        $form->get('id_cat')->setValueOptions(BDD::getSelecteurCategorie($this->serviceLocator)); 
        
        $form->bind($annonce); //pré-remplit
        $form->get('date_modif')->setValue(date('Y-m-d'));
        
        $form->get('submit')->setAttribute('value', 'Edition');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $annonceFormValidator = new AnnonceFormValidator(); 
            $form->setInputFilter($annonceFormValidator->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                BDD::getAnnonceTable($this->serviceLocator)->saveAnnonce($annonce);
                
                // Redirect to list of albums
                return $this->redirect()->toRoute('annonce');
            }
        }

        return array(
            'id_annonce' => $id_annonce,
            'form' => $form,
        );
    }

    public function deleteAction()
    {   
        $id_annonce = (int) $this->params()->fromRoute('id', 0);
        if (!$id_annonce) {
            return $this->redirect()->toRoute('annonce');
        }
                
        $request = $this->getRequest();
        if ($request->isPost()) {
                       
            $del = $request->getPost('del', 'Non');

            if ($del == 'Oui') {
                $id_annonce = (int) $request->getPost('id_annonce');
                BDD::getAnnonceTable($this->serviceLocator)->deleteAnnonce($id_annonce);
            
            }

            // Redirect to list of albums
            return $this->redirect($this->serviceLocator)->toRoute('annonce');
        }

        return array(
            'id_annonce'    => $id_annonce,
            'annonce' => BDD::getAnnonceTable($this->serviceLocator)->getAnnonce($id_annonce)
        );
    }
    
}