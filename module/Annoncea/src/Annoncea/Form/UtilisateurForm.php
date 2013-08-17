<?php
namespace Annoncea\Form;

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