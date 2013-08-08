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
    	 return new ViewModel(array(
            'annonces' => $this->getAnnonceTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new AnnonceForm();
        $form->get('submit')->setValue('Ajout'); //change le bouton "submit" en "ajout"

        $request = $this->getRequest();//récupère la requete pour voir si c'est la 1ere fois ou pas qu'on vient sur la page
        if ($request->isPost()) {//si c'est pas la 1ere fois
            $annonceFormValidator = new AnnonceFormValidator();
            $form->setInputFilter($annonceFormValidator->getInputFilter());
            $form->setData($request->getPost()); //on récupère ce qu'il y a dans la requete et on le met dans le formulaire
            $form->get('date_crea')->setValue(date('yyyy-MM-dd'));
            $form->get('date_modif')->setValue(date('yyyy-MM-dd'));
 
           
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
    }

    public function deleteAction()
    {
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