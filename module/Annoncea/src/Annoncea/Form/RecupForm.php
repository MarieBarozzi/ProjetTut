<?php
namespace Annoncea\Form;

use Zend\Form\Form;

class RecupForm extends Form
{
    
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('recup'); //appele le constructeur de Form
        $this->setAttribute('method', 'post');
        
             $this->add(array( 
            'name' => 'mail', 
            'type' => 'Email', 
            'attributes' => array( 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => 'Email : ', 
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