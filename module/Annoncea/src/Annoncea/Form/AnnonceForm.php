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
		
  		$this->add(array( //validé via csrf
  		 'type' => 'Select',
             'name' => 'type_annonce',
             'options' => array(
                     'label' => 'Type de l\'annonce : ',
                     'value_options' => array(
                             'offre' => 'Offre',
                             'demande' => 'Demande',
                     ),
             ),
            'attributes' => array( 
                'required' => 'required', 
            )
        ));		
		$this->add(array(
            'name' => 'prix',
            'type' => 'Text',
            'options' => array(
                'label' => 'Prix : ',
            ),
            'attributes' => array( 
                'required' => 'required', 
            )
        ));
		$this->add(array(
            'name' => 'etat',
            'type' => 'Select',
            'options' => array(
                'label' => 'Etat : ',
                'value_options' => array(
                             'neuf' => 'neuf',
                             'excellent'=> 'excellent',
                             'bon' => 'bon', 
                             'mauvais'=> 'mauvais',
                             'tresmauvais' => 'tres mauvais'
                        ),
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
            'attributes' => array( 
                'required' => 'required', 
            )
        )); 
         
      $this->add(array(
            'name' => 'id_dept',
            'type' => 'Select',
            'options' => array(
                'label' => 'Département : ',
            ),
           'attributes' => array( 
                'required' => 'required', 
            )
        ));
     

	$this->add(array(
		'type' => 'Hidden',
     	'name' => 'mail_auteur',
	));
		
        
     $this->add(array( 
            'name' => 'upload', 
            'type' => 'File', 
            'attributes' => array( 
                'multiple'=>true,
            ), 
            'options' => array( 
                'label' => 'Ajouter une ou plusieurs photos : ', 
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