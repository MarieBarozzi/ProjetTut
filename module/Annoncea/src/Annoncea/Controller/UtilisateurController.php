<?php
namespace Annoncea\Controller;

use Zend\Mvc\Controller\AbstractActionController;

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
         return array(
        );
    }
    
    public function inscriptionAction()
    {
         return array(
        );
    }
    
}