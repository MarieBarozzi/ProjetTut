<?php
namespace Annoncea\Form;

use Zend\Form\Form;

class AnnonceForm extends Form
{
	
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('annonce');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id_annonce',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'titre',
            'type' => 'Text',
            'options' => array(
                'label' => 'Titre : ',
            ),
            'attributes' => array( 
                'required' => 'required', 
            ),
        ));
        $this->add(array(
            'name' => 'descr',
            'type' => 'Textarea',
            'options' => array(
                'label' => 'Description : ',
            ),
        ));
		
  		$this->add(array( //pas de validateur ? 
  		 'type' => 'Select',
             'name' => 'type_annonce',
             'options' => array(
                     'label' => 'Type de l\'annonce : ',
                     'value_options' => array(
                             'offre' => 'Offre',
                             'demande' => 'Demande',
                     ),
             )
        ));		
		$this->add(array(
            'name' => 'prix',
            'type' => 'Text',
            'options' => array(
                'label' => 'Prix : ',
            ),
        ));
		$this->add(array(
            'name' => 'etat',
            'type' => 'Text',
            'options' => array(
                'label' => 'Etat : ',
            ),
        ));	
		$this->add(array(
            'name' => 'date_crea',
            'type' => 'Hidden',
        ));
		$this->add(array(
            'name' => 'date_modif',
            'type' => 'Hidden',
        ));
		
          $this->add(array(
            'name' => 'id_cat',
            'type' => 'Select',
            'options' => array(
                'label' => 'Catégorie : ',
            ),
        )); 
         
      $this->add(array(
            'name' => 'id_dept',
            'type' => 'Select',
            'options' => array(
                'label' => 'Département : ',
            ),
        ));
     

	$this->add(array(
		'type' => 'Email',
     	'name' => 'mail_auteur',
     	'options' => array(
         	'label' => 'Adresse Mail : '
     	),
	));
		
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
        
                
        $this->add(array( 
            'name' => 'csrf', 
            'type' => 'Zend\Form\Element\Csrf', 
        ));        
        
        
        
    }
}