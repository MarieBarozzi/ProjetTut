<?php
namespace Annonce\Form;

use Zend\InputFilter\Factory as InputFactory; 
use Zend\InputFilter\InputFilter; 
use Zend\InputFilter\InputFilterAwareInterface; 
use Zend\InputFilter\InputFilterInterface; 


class AnnonceFormValidator implements InputFilterAwareInterface 

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
                'name'     => 'id_annonce',
                'required' => true, //il le faut 
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
        
        

            $inputFilter->add($factory->createInput(array(
                'name'     => 'titre',
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
                'name' => 'descr', 
                'required' => false, 
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
            'name' => 'number', 
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
                'name'     => 'etat',
                'required' => false,
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
            'name' => 'date_crea', 
            'required' => true, 
            'filters' => array( 
                array('name' => 'StripTags'), 
                array('name' => 'StringTrim'), 
            ), 
        ))); 
 
            
        $inputFilter->add($factory->createInput(array(
            'name' => 'date_modif', 
            'required' => true, 
            'filters' => array( 
                array('name' => 'StripTags'), 
                array('name' => 'StringTrim'), 
            ), 
        ))); 
 
        $inputFilter->add($factory->createInput(array( 
            'name' => 'email', 
            'filters' => array( 
                array('name' => 'StripTags'), 
                array('name' => 'StringTrim'), 
            ), 
            'validators' => array( 
                array ( 
                    'name' => 'EmailAddress', 
                    'options' => array( 
                        'messages' => array( 
                            'emailAddressInvalidFormat' => 'L\'adresse email n\'est pas valide', 
                        ) 
                    ), 
                ), 
                array ( 
                    'name' => 'NotEmpty', 
                    'options' => array( 
                        'messages' => array( 
                            'isEmpty' => 'L\'adresse email est requise', 
                        ) 
                    ), 
                ), 
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
        
        
         $this->inputFilter = $inputFilter;
        
                   
        }

        return $this->inputFilter;
 
    } 
} 