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
        $form = new InscriptionForm();
                
        $form->get('id_dept')->setValueOptions($this->getChoixDepartement());
        $form->get('submit')->setValue('Inscription'); //change le bouton "submit" en "inscription"

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
    
}