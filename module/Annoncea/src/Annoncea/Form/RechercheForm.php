<?php
namespace Annoncea\Form;

use Zend\Form\Form;

class RechercheForm extends Form
{
	public function __construct($name = null){
		
		// Début de la construction.
		parent::__construct('recherche');
        $this->setAttribute('method', 'post');
		
		// Ajout des différentes parties du formulaire simple.
		// Ecrire un mot/phrase type qui sera recherché dans les titres d'annonces.
		$this->add(array(
			'name' => 'recherche',
			'type' => 'Text',
			'options' => array(
                'label' => 'Titre : ',
            ),
		));
		
		// selection d'une région.
		$this->add(array(
			'name' => 'id_reg',
			'type' => 'Select',
			'options' => array(
                'label' => 'Région : ',
                'empty_option' => 'Toute la France',
            ),
		));
		
		// selection d'un département. -->Faire apparaître uniquement les département d'une région.
		$this->add(array(
			'name' => 'id_dept',
			'type' => 'Select',
			'options' => array(
                'label' => 'Departement : ',
                'empty_option' => 'Toute la France',
            ),
		));
			
		$this->add(array(
			'name' => 'id_cat',
			'type' => 'Select',
			'options' => array(
				'label' => 'Catégorie : ',
				'empty_option' => 'Toute catégorie',
			),
		));
  
        $this->add(array(
            'name' => 'prixmin',
            'type' => 'Text',
            'options' => array(
                'label' => 'Prix min : ',
            ),
        ));
        

        $this->add(array(
            'name' => 'prixmax',
            'type' => 'Text',
            'options' => array(
                'label' => 'Prix max : ',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'type_annonce',
            'type' => 'Select',
            'options' => array(
                'label' => 'Type annonce : ',
                'empty_option' => 'Offres et demandes',
                'value_options' => array(
                             'offre' => 'Offres',
                             'demande' => 'Demandes',
                        ),
            ),
        ));
        
                
        $this->add(array(
            'name' => 'etat',
            'type' => 'Select',
            'options' => array(
                'label' => 'Etat : ',
                'empty_option' => 'Indifferent',
                'value_options' => array(                         
                             4 => 'neuf',
                             3 => 'excellent',
                             2 => 'bon', 
                             1 => 'mauvais',
                             0 => 'tres mauvais'
                ),
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