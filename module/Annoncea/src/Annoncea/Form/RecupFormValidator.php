<?php
namespace Annoncea\Form;

use Zend\InputFilter\Factory as InputFactory; 
use Zend\InputFilter\InputFilter; 
use Zend\InputFilter\InputFilterAwareInterface; 
use Zend\InputFilter\InputFilterInterface; 


class RecupFormValidator implements InputFilterAwareInterface 

{ 
    protected $inputFilter; 
   
    
    public function setInputFilter(InputFilterInterface $inputFilter) 
    { 
        throw new \Exception("Not used"); 
    } 
    
    public function getInputFilter() 
    { 
        if (!$this->inputFilter) 
        { 
            $inputFilter = new InputFilter(); 
            $factory = new InputFactory(); 
            
            $inputFilter->add($factory->createInput(array(
            'name' => 'mail', 
            'filters' => array( 
                array('name' => 'StripTags'), 
                array('name' => 'StringTrim'), 
            ), 
            'validators' => array( 
                array ( 
                    'name' => 'EmailAddress', 
                ), 
                array ( 
                    'name' => 'NotEmpty', 
 
                ),
                 array(
                     'name'    => 'StringLength',
                     'options' => array(
                        'encoding' => 'UTF-8', 
                        'min'      => 1,
                        'max'      => 32, 
                     ),
                ), 
                
                ))));
 
         $this->inputFilter = $inputFilter;
        
                   
        }

        return $this->inputFilter;
        
    }
        
}