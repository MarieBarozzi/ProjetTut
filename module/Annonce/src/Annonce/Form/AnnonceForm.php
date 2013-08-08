<?php
namespace Annonce\Form;

use Zend\Form\Form;

class AnnonceForm extends Form
{

	public $visible; //
	
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
                'label' => 'Titre',
            ),
        ));
        $this->add(array(
            'name' => 'descr',
            'type' => 'Textarea',
            'options' => array(
                'label' => 'Description',
            ),
        ));
		
  		$this->add(array(
            'name' => 'type_annonce',
            'type' => 'Text',
            'options' => array(
                'label' => 'Type de l annonce',
            ),
        ));		
		$this->add(array(
            'name' => 'prix',
            'type' => 'Text',
            'options' => array(
                'label' => 'Prix',
            ),
        ));
		$this->add(array(
            'name' => 'etat',
            'type' => 'Text',
            'options' => array(
                'label' => 'Etat',
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
             'type' => 'Select',
             'name' => 'categorie',
             'options' => array(
                     'label' => 'Categorie',
                     'value_options' => array(/*comment recuperer les bons champs*/
                             '0' => 'French',
                             '1' => 'English',
                             '2' => 'Japanese',
                             '3' => 'Chinese',
                     ),
             )
     ));
	  $this->add(array(
             'type' => 'Select',
             'name' => 'departement',
             'options' => array(
                     'label' => 'Departement',
                     'value_options' => array(/*comment recuperer les bons champs*/
                             '0' => 'French',
                             '1' => 'English',
                             '2' => 'Japanese',
                             '3' => 'Chinese',
                     ),
             )
     ));
	$this->add(array(
		'type' => 'Email',
     	'name' => 'email',
     	'options' => array(
         	'label' => 'Email Address'
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
    }
}