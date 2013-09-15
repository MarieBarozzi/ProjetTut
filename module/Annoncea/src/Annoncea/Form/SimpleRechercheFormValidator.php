<?php
namespace Annoncea\Form;

use Zend\InputFilter\Factory as InputFactory; 
use Zend\InputFilter\InputFilter; 
use Zend\InputFilter\InputFilterAwareInterface; 
use Zend\InputFilter\InputFilterInterface; 


class SimpleRechercheFormValidator implements InputFilterAwareInterface 

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

			// Argument à vérifié : titre/phrase recherché.
            $inputFilter->add($factory->createInput(array(
                'name'     => 'nom',
                'required' => false, 
                'filters'  => array(
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'),
                ),
				'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 3,// nombre minimal
                            'max'      => 100, // nombre maximal --> Une phrase.
                        ),
                    ),
                ),
               
            )));
         $this->inputFilter = $inputFilter;                 
        }
        return $this->inputFilter;
    } 
} 