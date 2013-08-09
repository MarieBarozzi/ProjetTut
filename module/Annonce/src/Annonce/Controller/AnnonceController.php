<?php
namespace Annonce\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Annonce\Model\Annonce;
use Annonce\Form\AnnonceForm;
use Annonce\Form\AnnonceFormValidator;

class AnnonceController extends AbstractActionController
{
	
	protected $annonceTable;
	
    public function indexAction()
    {
    	 return array(
            'annonces' => $this->getAnnonceTable()->fetchAll(),
        );
    }

    public function addAction()
    {
        $form = new AnnonceForm();
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
            $this->annonceTable = $sm->get('Annonce\Model\AnnonceTable');
        }
        return $this->annonceTable;
    }
}