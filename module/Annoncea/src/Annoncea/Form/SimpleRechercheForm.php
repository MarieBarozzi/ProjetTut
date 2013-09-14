<?php
namespace Annoncea\Form;

use Zend\Form\Form;

class SimpleRechercheForm extends Form
{
	public function __construct($name = null){
		
		// Début de la construction.
		parent::__construct('recherche');
        $this->setAttribute('method', 'post');
		
		// Ajout des différentes parties du formulaire simple.
		// Ecrire un mot/phrase type qui sera recherché dans les titres d'annonces.
		$this->add(array(
			'name' => 'nom',
			'type' => 'Text',
			'options' => array(
                'label' => 'Recherche par titre : ',
            ),
		));
		
		// selection d'une région.
		$this->add(array(
			'name' => 'id_reg',
			'type' => 'Select',
			'options' => array(
                'label' => 'Région de Recherche : ',
            ),
		));
		
		// selection d'un département. -->Faire apparaître uniquement les département d'une région.
		$this->add(array(
			'name' => 'id_dept',
			'type' => 'Select',
			'options' => array(
                'label' => 'Région de Recherche : ',
            ),
		));
		
		// catégorie dans laquelle recherché.	
		$this->add(array(
			'name' => 'id_cat',
			'type' => 'Select',
			'options' => array(
				'label' => 'Catégorie : ',
			),
		));
		
	}

}