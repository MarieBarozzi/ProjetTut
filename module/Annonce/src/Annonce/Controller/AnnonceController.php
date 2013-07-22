<?php
namespace Annonce\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

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