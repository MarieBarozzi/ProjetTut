<?php
namespace Annoncea\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Annoncea\Model\Annonce;
use Annoncea\Model\Favoris;
use Annoncea\Model\Photo;
use Annoncea\Form\AnnonceForm;
use Annoncea\Form\AnnonceFormValidator;
use Annoncea\Form\RechercheForm;
use Annoncea\Form\RechercheFormValidator;
use Annoncea\Model\BaseAnnoncea as BDD;
use Zend\Authentication\AuthenticationService;

class AnnonceController extends AbstractActionController
{
	public function homeAction(){
	    $auth = new AuthenticationService();
        $retour = array();
        if($auth->hasIdentity()) {
            $retour['co'] = true;
        }
        return $retour;
	}
    
    public function indexAction()
    {         
        $auth = new AuthenticationService();
        if($auth->hasIdentity()) {
            $retour['co'] = true;
        }   
        
        $form = new RechercheForm();
        $form->get('submit')->setValue('Chercher');
        $form->get('id_dept')->setValueOptions(BDD::getSelecteurDepartement($this->serviceLocator));
        $form->get('id_cat')->setValueOptions(BDD::getSelecteurCategorie($this->serviceLocator));  
        $form->get('id_reg')->setValueOptions(BDD::getSelecteurRegion($this->serviceLocator));     
        
        $request = $this->getRequest();
        if($request->isPost()){//si ça vient du formulaire
            $form->setData($request->getPost()); //remplit l'objet formulaire avec les données qui viennent de la requete post
            $page = 1; //si on fait une nouvelle recherche, on revient à la page 1
        } else {
            $form->setData(array());//dit au formulaire que ses champs ont été remplis (consiedre pas que les champs sont renseignés si on set les valeurs directement)
            $form->get('recherche')->setValue(urldecode($this->params()->fromRoute('recherche', null)));
            $form->get('prixmin')->setValue(urldecode($this->params()->fromRoute('prixmin', null)));
            $form->get('prixmax')->setValue(urldecode($this->params()->fromRoute('prixmax', null)));
            $form->get('id_cat')->setValue((int) urldecode($this->params()->fromRoute('id_cat', null)));
            $form->get('id_dept')->setValue(urldecode($this->params()->fromRoute('id_dept', null)));
            $form->get('type_annonce')->setValue(urldecode($this->params()->fromRoute('type_annonce', null))); 
            $form->get('etat')->setValue(urldecode($this->params()->fromRoute('etat', null)));  
            $form->get('id_reg')->setValue((int) $this->params()->fromRoute('id_reg', null));
            $form->get('rechtitre')->setChecked((boolean) urldecode($this->params()->fromRoute('rechtitre', null)));
             
            $page = (int) urldecode($this->params()->fromRoute('page', 1));
        }
        
        $param = array();
        $annonces = array();
        $metaAnnonces = array();
        
        $rechercheFormValidator = new RechercheFormValidator(); 
            $form->setInputFilter($rechercheFormValidator->getInputFilter());
            if ($form->isValid()) {
                $param['recherche'] = $form->get('recherche')->getValue();
                $param['prixmin'] = $form->get('prixmin')->getValue();
                $param['prixmax'] = $form->get('prixmax')->getValue();
                $param['id_cat'] = $form->get('id_cat')->getValue();
                $param['id_dept'] = $form->get('id_dept')->getValue();
                $param['type_annonce'] = $form->get('type_annonce')->getValue();
                $param['etat'] = $form->get('etat')->getValue();
                $param['id_reg'] = $form->get('id_reg')->getValue();
                $param['rechtitre'] = $form->get('rechtitre')->isChecked();
                
                $annoncesResultSet=BDD::getAnnonceTable($this->serviceLocator)->filtrageStrict($param['prixmin'], $param['prixmax'], $param['id_cat'], $param['id_dept'], $param['type_annonce'], $param['id_reg'], $param['etat']);
        
                foreach($annoncesResultSet as $annonce) {
                    if($annonce->pertinent($param['recherche'], $param['rechtitre'])) {
                        $annonces[$annonce->id_annonce] = $annonce;
                        $metaAnnonces[$annonce->id_annonce] = array(
                            'photo'=> BDD::getPhotoTable($this->serviceLocator)->getByIdAnnonce($annonce->id_annonce)->current(),
                            'departement' => BDD::getDepartementTable($this->serviceLocator)->getDepartement($annonce->id_dept),
                            'categorie' => BDD::getCategorieTable($this->serviceLocator)->getCategorie($annonce->id_cat),
                        );
                    }     
                }
            }
        
        
        $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($annonces));
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(10);
        
        
        $retour['form'] = $form;
        $retour['meta'] = $metaAnnonces;
        $retour['pagination'] = $paginator; //contient les annonces
        $retour['param'] = array_filter($param);
        
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
                $annonce->id_reg = BDD::getDepartementTable($this->serviceLocator)->getDepartement($annonce->id_dept)->id_reg;
              
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
                $annonce->id_reg = BDD::getDepartementTable($this->serviceLocator)->getDepartement($annonce->id_dept)->id_reg;
                
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
           // return $this->redirect($this->serviceLocator)->toRoute('annonce');
            return $this->redirect($this->serviceLocator)->toRoute('utilisateur', array('action'=>'mesannonces'));
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
        
    }
    
    public function deletefavorisAction() {
        $id_annonce = (int) $this->params()->fromRoute('id', 0);
        BDD::getFavorisTable($this->serviceLocator)->deleteFavoris($id_annonce);
        return $this->redirect($this->serviceLocator)->toRoute('utilisateur', array('action'=>'mesfavoris'));
    }
    
    /*public function testrechercheAction(){
        $prixmin = null;
        $prixmax = null;
        $id_cat = null;
        $id_dept = null;
        $type_annonce = null;    
        $id_reg = null;    
        
        $form = new RechercheForm();
        $form->get('submit')->setValue('Chercher');
        $form->get('id_dept')->setValueOptions(BDD::getSelecteurDepartement($this->serviceLocator));
        $form->get('id_cat')->setValueOptions(BDD::getSelecteurCategorie($this->serviceLocator));  
        $form->get('id_reg')->setValueOptions(BDD::getSelecteurRegion($this->serviceLocator));  
        
        $request = $this->getRequest();
        if($request->isPost()){//si ça vient du formulaire
            $form->setData($request->getPost()); //remplit l'objet formulaire avec les données qui viennent de la requete post
            $rechercheFormValidator = new RechercheFormValidator(); 
            $form->setInputFilter($rechercheFormValidator->getInputFilter());
            if ($form->isValid()) {
                $prixmin = $form->get('prixmin')->getValue();
                $prixmax = $form->get('prixmax')->getValue();
                $id_cat = $form->get('id_cat')->getValue();
                $id_dept = $form->get('id_dept')->getValue();
                $type_annonce = $form->get('type_annonce')->getValue();    
                $id_reg = $form->get('id_reg')->getValue();  

             }        
        }
        $annoncesBDD=BDD::getAnnonceTable($this->serviceLocator)->filtrageStrict($prixmin, $prixmax, $id_cat, $id_dept, $type_annonce, $id_reg);
        $annonces = array();
        foreach($annoncesBDD as $annonce){
            $annonces[$annonce->id_annonce] = $annonce;
        }
        $retour['annonces'] = $annonces;
        $retour['form'] = $form;
        return $retour;
    }*/
    

    
}