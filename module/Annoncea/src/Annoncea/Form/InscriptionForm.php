<?php
namespace Annoncea\Form;

use Zend\Form\Form;

class InscriptionForm extends Form
{
    
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('inscription');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'nom',
            'type' => 'Text',
             'options' => array(
                'label' => 'Nom',
            ),
            'attributes' => array( 
                'required' => 'required', 
             ),
        ));
        $this->add(array(
            'name' => 'prenom',
            'type' => 'Text',
            'options' => array(
                'label' => 'Prénom',
            ),
            'attributes' => array( 
                'required' => 'required', 
            ),
        ));
         $this->add(array(
            'name' => 'pseudo',
            'type' => 'Text',
            'options' => array(
                'label' => 'Pseudonyme',
            ),
            'attributes' => array( 
                'required' => 'required', 
            ),
        ));
        
        $this->add(array( 
            'name' => 'statut', 
            'type' => 'Radio', 
            'attributes' => array( 
                'required' => 'required', 
                'value' => 'particulier', 
            ), 
            'options' => array( 
                'label' => 'Statut', 
                'value_options' => array(
                    'particulier' => 'Particulier', 
                    'professionnel' => 'Professionnel', 
                ),
            ), 
        )); 
         $this->add(array(
            'name' => 'adresse',
            'type' => 'Text',
            'options' => array(
                'label' => 'Adresse',
            ),
            'attributes' => array( 
                'required' => 'required', 
            ),
        ));
        
         $this->add(array(
            'name' => 'cp',
            'type' => 'Text',
            'options' => array(
                'label' => 'Code Postal',
            ),
            'attributes' => array( 
                'required' => 'required', 
            ),
        ));
        
        $this->add(array(
            'name' => 'ville',
            'type' => 'Text',
            'options' => array(
                'label' => 'Ville',
            ),
            'attributes' => array( 
                'required' => 'required', 
            ),
        ));
        
  
        $this->add(array(
            'name' => 'tel',
            'type' => 'Text',
            'options' => array(
                'label' => 'Téléphone',
            ),
            'attributes' => array( 
                'required' => 'required', 
             ),
        ));
          
        $this->add(array(
            'name' => 'id_dept',
            'type' => 'Select',
            'options' => array(
                'label' => 'Departement',
            ),
            'attributes' => array( 
                'required' => 'required', 
             ),
        ));
        
    }
    
}