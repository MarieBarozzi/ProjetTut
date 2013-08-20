<?php
namespace Annoncea\Form;

use Zend\InputFilter\Factory as InputFactory; 
use Zend\InputFilter\InputFilter; 
use Zend\InputFilter\InputFilterAwareInterface; 
use Zend\InputFilter\InputFilterInterface; 


class InscriptionFormValidator implements InputFilterAwareInterface 

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
                'name'     => 'nom',
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
                            'max'      => 32, 
                        ),
                    ),
                ),
            )));
        
        

            $inputFilter->add($factory->createInput(array(
                'name'     => 'prenom',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'), //pour enlever le html
                    array('name' => 'StringTrim'), //enlève les espaces au debut et à la fin
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8', //encodage de caractères
                            'min'      => 1,
                            'max'      => 32, //voir dans la base
                        ),
                    ),
                ),
            )));

           $inputFilter->add($factory->createInput(array(
                'name' => 'pseudo', 
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
                            'max'      => 32, 
                        ),
                    ),
                ),  
        ))); 
        $inputFilter->add($factory->createInput(array(
            'name' => 'statut', 
            'required' => true, 
            'filters' => array( 
                array('name' => 'StripTags'), 
                array('name' => 'StringTrim'), 
            ), 
        ))); 
 
        $inputFilter->add($factory->createInput(array(
                'name'     => 'adresse',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8', 
                            'min'      => 1,
                            'max'      => 32, 
                        ),
                    ),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'cp',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8', 
                            'min'      => 1,
                            'max'      => 32, 
                        ),
                    ),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'ville',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8', 
                            'min'      => 1,
                            'max'      => 32, 
                        ),
                    ),
                ),
            )));
             $inputFilter->add($factory->createInput(array(
                'name'     => 'tel',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8', 
                            'min'      => 1,
                            'max'      => 32, 
                        ),
                    ),
                ),
            )));
        
         $this->inputFilter = $inputFilter;
        
                   
        }

        return $this->inputFilter;
 
    } 
}