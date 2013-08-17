<?php
namespace Annoncea\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Annoncea\Model\Annonce;
use Annoncea\Form\AnnonceForm;
use Annoncea\Form\AnnonceFormValidator;

class AnnonceController extends AbstractActionController
{
	
	protected $annonceTable;
    protected $departementTable; 
    protected $categorieTable; 
	
    public function indexAction()
    {
    	 return array(
            'annonces' => $this->getAnnonceTable()->fetchAll(),
        );
    }

    public function addAction()
    {
        
        
        $form = new AnnonceForm();
        
        
        $form->get('id_dept')->setValueOptions($this->getChoixDepartement());
        $form->get('id_cat')->setValueOptions($this->getChoixCategorie());
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
                $this->getAnnonceTable()->saveAnnonce($annonce);

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
            $annonce = $this->getAnnonceTable()->getAnnonce($id_annonce);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('annonce', array(
                'action' => 'index'
            ));
        }
       
        
        $form  = new AnnonceForm();
        $form->get('id_dept')->setValueOptions($this->getChoixDepartement());
        $form->get('id_cat')->setValueOptions($this->getChoixCategorie());
        
        
        $form->bind($annonce); //pré-remplit
        $form->get('date_modif')->setValue(date('Y-m-d'));
        
        $form->get('submit')->setAttribute('value', 'Edition');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $annonceFormValidator = new AnnonceFormValidator(); 
            $form->setInputFilter($annonceFormValidator->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getAnnonceTable()->saveAnnonce($annonce);

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
                $this->getAnnonceTable()->deleteAnnonce($id_annonce);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('annonce');
        }

        return array(
            'id_annonce'    => $id_annonce,
            'annonce' => $this->getAnnonceTable()->getAnnonce($id_annonce)
        );
    }
	
	
	public function getAnnonceTable()
    {
        if (!$this->annonceTable) {
            $sm = $this->getServiceLocator();
            $this->annonceTable = $sm->get('Annoncea\Model\AnnonceTable');
        }
        return $this->annonceTable;
    }
    
    public function getDepartementTable()
    {
        if (!$this->departementTable) {
            $sm = $this->getServiceLocator();
            $this->departementTable = $sm->get('Annoncea\Model\DepartementTable');
        }
        return $this->departementTable;
    }
    
    public function getCategorieTable()
    {
        if (!$this->categorieTable) {
            $sm = $this->getServiceLocator();
            $this->categorieTable = $sm->get('Annoncea\Model\CategorieTable');
        }
        return $this->categorieTable;
    }
    
    public function getChoixDepartement() {
        $departements = $this->getDepartementTable()->fetchAll();
        $choixDepartement = array();
        foreach ($departements as $departement) {
            $choixDepartement[$departement->id_dept] = $departement->id_dept . ' - ' . $departement->lib_dept;
        }    
        return $choixDepartement;     
    }
    
    public function getChoixCategorie() {
        $categories = $this->getCategorieTable()->fetchAll();
        $choixCategorie = array();
        foreach ($categories as $categorie) {
            $choixCategorie[$categorie->id_cat] = $categorie->lib_cat;
        } 
        return $choixCategorie;  
    }
    
}