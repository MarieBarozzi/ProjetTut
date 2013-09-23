<?php
namespace Annoncea\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Annoncea\Model\Annonce;
use Annoncea\Model\Favoris;
use Annoncea\Model\Photo;
use Annoncea\Form\AnnonceForm;
use Annoncea\Form\AnnonceFormValidator;
use Annoncea\Model\BaseAnnoncea as BDD;
use Zend\Authentication\AuthenticationService;

class AnnonceController extends AbstractActionController
{
	
    public function indexAction()
    {         
        $auth = new AuthenticationService();
        if($auth->hasIdentity()) {
            $retour['co'] = true;
        }
        
        $annoncesResultSet = BDD::getAnnonceTable($this->serviceLocator)->fetchAll();
        $annonces = array();
        $metaAnnonces = array();
        foreach($annoncesResultSet as $annonce) {
            $annonces[$annonce->id_annonce] = $annonce;
            $metaAnnonces[$annonce->id_annonce] = array(
                'photo'=> BDD::getPhotoTable($this->serviceLocator)->getByIdAnnonce($annonce->id_annonce)->current(),
                'departement' => BDD::getDepartementTable($this->serviceLocator)->getDepartement($annonce->id_dept),
                'categorie' => BDD::getCategorieTable($this->serviceLocator)->getCategorie($annonce->id_cat),
            );
        }
        
        
        $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($annonces));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page', 1));
        $paginator->setItemCountPerPage(1);
        
        $retour['meta'] = $metaAnnonces;
        $retour['pagination'] = $paginator; //contient les annonces
    	return $retour;
    }

    public function annonceAction() {
        $auth = new AuthenticationService();
        if($auth->hasIdentity()) {
            $retour['co'] = true;
        }
        
        //si il n'y a pas d'id d'annonce dans url
        $id_annonce = (int) $this->params()->fromRoute('id', 0);
        if (!$id_annonce) {
            return $this->redirect()->toRoute('annonce', array(
                'action' => 'index'
            ));
        }
                
        //si l'id annonce n'est pas dans la base
        try {
            $retour['annonce'] = BDD::getAnnonceTable($this->serviceLocator)->getAnnonce($id_annonce);
                 
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('annonce', array(
                'action' => 'index'
            ));
        }

       $metaAnnonces[$retour['annonce']->id_annonce] = array(
                'photo'=> BDD::getPhotoTable($this->serviceLocator)->getByIdAnnonce($retour['annonce']->id_annonce),
                'departement' => BDD::getDepartementTable($this->serviceLocator)->getDepartement($retour['annonce']->id_dept),
                'categorie' => BDD::getCategorieTable($this->serviceLocator)->getCategorie($retour['annonce']->id_cat),
            );
        $retour['meta'] = $metaAnnonces;
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
         
        $retour['co'] = true; 
               
        $form = new AnnonceForm();
        
        $form->get('id_dept')->setValueOptions(BDD::getSelecteurDepartement($this->serviceLocator));
        $form->get('id_cat')->setValueOptions(BDD::getSelecteurCategorie($this->serviceLocator));   
    
        $form->get('mail_auteur')->setValue($auth->getIdentity());
        $form->get('date_crea')->setValue(date('Y-m-d H:i:s'));
        $form->get('date_modif')->setValue(date('Y-m-d H:i:s'));
        $form->get('submit')->setValue('Ajout'); //change le bouton "submit" en "ajout"

        $request = $this->getRequest();//récupère la requete pour voir si c'est la 1ere fois ou pas qu'on vient sur la page
        if ($request->isPost()) {//quand on vient depuis le bouton submit  
             $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $annonceFormValidator = new AnnonceFormValidator();
            $form->setInputFilter($annonceFormValidator->getInputFilter());
            $form->setData($post); //on récupère ce qu'il y a dans la requete et on le met dans le formulaire
            
            if ($form->isValid()) { //si il passe le validateur 
                $annonce = new Annonce();
                $annonce->exchangeArray($form->getData()); //remplit l'objet à partir d'un tableau qu'on récupère du formulaire            
                $id_annonce = BDD::getAnnonceTable($this->serviceLocator)->saveAnnonce($annonce);
                 
                //pour les images  
                $fichiers = $form->get('upload')->getValue();  
                foreach($fichiers as $fichier){
                    $photo = new Photo(); 
                    $photo->id_annonce = $id_annonce;
                    $id_photo = BDD::getPhotoTable($this->serviceLocator)->savePhoto($photo);
                    
                    $adapter = new \Zend\File\Transfer\Adapter\Http(); 
                    $adapter->setDestination($_SERVER['CONTEXT_DOCUMENT_ROOT'].$this->getRequest()->getBasePath().'/photos');
                    $adapter->receive($fichier['name']);
                    rename($_SERVER['CONTEXT_DOCUMENT_ROOT'].$this->getRequest()->getBasePath().'/photos/'.$fichier['name'],
                        $_SERVER['CONTEXT_DOCUMENT_ROOT'].$this->getRequest()->getBasePath().'/photos/'.$id_photo);
                }
                return $this->redirect()->toRoute('annonce');
              }
        }
        $retour['form'] = $form;
        return $retour;
    }
        
      

    public function editAction()
    {
        $auth = new AuthenticationService();
        if($auth->hasIdentity()) {
            $retour['co'] = true;
        }
        
        //si il n'y a pas d'id d'annonce dans url
        $id_annonce = (int) $this->params()->fromRoute('id', 0);
        if (!$id_annonce) {
            return $this->redirect()->toRoute('annonce', array(
                'action' => 'add'
            ));
        }
                
        //si l'id annonce n'est pas dans la base
        try {
            $annonce = BDD::getAnnonceTable($this->serviceLocator)->getAnnonce($id_annonce);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('annonce', array(
                'action' => 'index'
            ));
        }
       
       if($annonce->mail_auteur !== $auth->getIdentity()){
           return $this->redirect()->toRoute('annonce', array(
                'action' => 'index' ));
       }
            
        $form  = new AnnonceForm();
        
        $form->get('id_dept')->setValueOptions(BDD::getSelecteurDepartement($this->serviceLocator));
        $form->get('id_cat')->setValueOptions(BDD::getSelecteurCategorie($this->serviceLocator)); 
        
        $form->bind($annonce); //pré-remplit
        $form->get('date_modif')->setValue(date('Y-m-d H:i:s'));
        
        $form->get('submit')->setAttribute('value', 'Edition');

        $request = $this->getRequest();
        if ($request->isPost()) {
             $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
 
            $annonceFormValidator = new AnnonceFormValidator(); 
            $form->setInputFilter($annonceFormValidator->getInputFilter());
            $form->setData($post);

            if ($form->isValid()) {
                BDD::getAnnonceTable($this->serviceLocator)->saveAnnonce($annonce);
                
                $fichiers = $form->get('upload')->getValue();  
                foreach($fichiers as $fichier){
                    $photo = new Photo(); 
                    $photo->id_annonce = $id_annonce;
                    $id_photo = BDD::getPhotoTable($this->serviceLocator)->savePhoto($photo);
                    
                    $adapter = new \Zend\File\Transfer\Adapter\Http(); 
                    $adapter->setDestination($_SERVER['CONTEXT_DOCUMENT_ROOT'].$this->getRequest()->getBasePath().'/photos');
                    $adapter->receive($fichier['name']);
                    rename($_SERVER['CONTEXT_DOCUMENT_ROOT'].$this->getRequest()->getBasePath().'/photos/'.$fichier['name'],
                        $_SERVER['CONTEXT_DOCUMENT_ROOT'].$this->getRequest()->getBasePath().'/photos/'.$id_photo);
                }
                return $this->redirect()->toRoute('annonce');
            }
                  
        }

        $retour['id_annonce'] = $id_annonce;
        $retour['form'] = $form; 
        return $retour;
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
    
    
    //Gestion de la mise en favoris 
    
    
    //mettre en favoris 
    public function favorisAction() {
        //besoins d'etre co
        $auth = new AuthenticationService();
        if(!$auth->hasIdentity()) {
             return $this->redirect()->toRoute('utilisateur', array(
                'action' => 'connexion' //ajouter un message lui disant qu'il doit etre co pour ajouter une annonce ? 
            ));
        }
        $favoris = new Favoris(); 
        $favoris->mail = $auth->getIdentity();
        $favoris->id_annonce = (int) $this->params()->fromRoute('id', 0);
        BDD::getFavorisTable($this->serviceLocator)->saveFavoris($favoris);
       
        return $this->redirect($this->serviceLocator)->toRoute('annonce', array('action'=>'annonce', 'id' => $favoris->id_annonce));
         //mais je preferais rester sur la même page !!!!!!!!!
        
    }

    //voir ces favoris 
    //plutot une fonction dans utilisateur
    //qui appelera getByMail
   
    
    public function deletefavorisAction() {
        $id_annonce = (int) $this->params()->fromRoute('id', 0);
        BDD::getFavorisTable($this->serviceLocator)->deleteFavoris($id_annonce);
        return $this->redirect($this->serviceLocator)->toRoute('utilisateur', array('action'=>'mesfavoris'));
        //mais je preferais rester sur la même page !!!!!!!!!
    }
    
    
}