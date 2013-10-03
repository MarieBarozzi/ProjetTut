<?php
namespace Annoncea\Form;

use Zend\InputFilter\Factory as InputFactory; 
use Zend\InputFilter\InputFilter; 
use Zend\InputFilter\InputFilterAwareInterface; 
use Zend\InputFilter\InputFilterInterface; 


class RechercheFormValidator implements InputFilterAwareInterface 

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
            'name' => 'primin', 
            'required' => false, 
            'filters' => array( 
                array('name' => 'StripTags'), 
                array('name' => 'StringTrim'), 
            ), 
            'validators' => array( 
                array ( 
                    'name' => 'float', 
                ), 
 
            ), 
        ))); 
			     
            $inputFilter->add($factory->createInput(array(
            'name' => 'primax', 
            'required' => false, 
            'filters' => array( 
                array('name' => 'StripTags'), 
                array('name' => 'StringTrim'), 
            ), 
            'validators' => array( 
                array ( 
                    'name' => 'float', 
                ), 
 
            ), 
        ))); 
			
        $inputFilter->add($factory->createInput(array(
            'name' => 'id_cat', 
            'required' => false, 

        ))); 
			
        $inputFilter->add($factory->createInput(array(
            'name' => 'id_reg', 
            'required' => false, 

        ))); 
           
        $inputFilter->add($factory->createInput(array(
            'name' => 'id_dept', 
            'required' => false, 

        ))); 
           
        $inputFilter->add($factory->createInput(array(
            'name' => 'type_annonce', 
            'required' => false, 

        ))); 
        
         $inputFilter->add($factory->createInput(array(
            'name' => 'etat', 
            'required' => false, 

        ))); 
             	
			
			// Argument à vérifié : titre/phrase recherché.
            $inputFilter->add($factory->createInput(array(
                'name'     => 'recherche',
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