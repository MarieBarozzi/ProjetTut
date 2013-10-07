<?php
namespace Annoncea\Form;

use Zend\Captcha; 
use Zend\Form\Element; 
use Zend\Form\Form; 

class MessageForm extends Form 

{ 
    public function __construct($name = null) 
    { 
        parent::__construct('message'); 
        
        $this->setAttribute('method', 'post'); 
        
        $this->add(array( 
            'name' => 'titre', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'placeholder' => 'Type something...', 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => 'Text', 
            ), 
        )); 
 
        $this->add(array( 
            'name' => 'contenu', 
            'type' => 'Zend\Form\Element\Textarea', 
            'attributes' => array( 
                'required' => 'required', 
            ), 
            'options' => array( 
            ), 
        )); 
 

        $this->add(array(
            'name' => 'mail_auteur',
            'type' => 'Hidden',
        ));


        $this->add(array(
            'name' => 'id_annonce',
            'type' => 'Hidden',
        ));   

        $this->add(array( 
            'name' => 'csrf', 
            'type' => 'Zend\Form\Element\Csrf', 
        ));     
    } 
} 