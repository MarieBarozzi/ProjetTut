<?php
namespace Annoncea\Form;

use Zend\InputFilter\Factory as InputFactory; 
use Zend\InputFilter\InputFilter; 
use Zend\InputFilter\InputFilterAwareInterface; 
use Zend\InputFilter\InputFilterInterface; 

class MessageFormValidator implements InputFilterAwareInterface 

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
            'name' => 'titre', 
            'required' => true, 
            'filters' => array( 
                array('name' => 'StripTags'), 
                array('name' => 'StringTrim'), 
            ), 
               'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8', 
                            'min'      => 1,
                        ),
                    ),
                ),
        ))); 
 
        $inputFilter->add($factory->createInput(array(
            'name' => 'contenu', 
            'required' => true, 
            'filters' => array( 
                array('name' => 'StripTags'), 
                array('name' => 'StringTrim'), 
            ), 
               'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8', 
                            'min'      => 1,
                        ),
                    ),
                ),
        ))); 

       $inputFilter->add($factory->createInput(array(
            'name' => 'mail_auteur',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
                ),
        )));

        $inputFilter->add($factory->createInput(array(
            'name' => 'id_annonce',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
                ),
        )));
        
		$this->inputFilter = $inputFilter;      
		
        }
		
		return $this->inputFilter;
        
    } 
} 