<?php
namespace Annoncea\Form;

use Zend\InputFilter\Factory as InputFactory; 
use Zend\InputFilter\InputFilter; 
use Zend\InputFilter\InputFilterAwareInterface; 
use Zend\InputFilter\InputFilterInterface; 


class InscriptionFormValidator implements InputFilterAwareInterface 

{ 
    protected $inputFilter; 
    private $dbAdapter; //lien avec db qui sert pour le validateur qui vérifie que l'email (entrée) n'est pas déjà dans la table
   
   
    public function setDbAdapter($dbAdapter) {
        $this->dbAdapter = $dbAdapter;
    }
   
    
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
                    array ( 
                        'name' => 'digits', 
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
                            'max'      => 20, 
                        ),
                    ),
                ),
            )));
        
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
                array( 
                    'name' => 'Db\NoRecordExists', // Db\Blabla car dans un sous dossier de Validator
                    'options' => array(
                         'table' => 'utilisateur',
                         'field' => 'mail',
                         'adapter' => $this->dbAdapter,
                     ),
                ),  
            ), 
        ))); 
        
        
        $inputFilter->add($factory->createInput(array(
            'name' => 'mdp', 
            'filters' => array( 
                array('name' => 'StripTags'), 
                array('name' => 'StringTrim'), 
            ), 
            'validators' => array(
                   array(
                     'name'    => 'StringLength',
                     'options' => array(
                        'encoding' => 'UTF-8', 
                        'min'      => 8,
                        'max'      => 32, 
                     ),
                ),   
            ), 
        ))); 
 
        $inputFilter->add($factory->createInput(array(
            'name' => 'mdp_verif', 
            'filters' => array( 
                array('name' => 'StripTags'), 
                array('name' => 'StringTrim'), 
            ), 
            'validators' => array( 
                array ( 
                    'name' => 'identical', 
                    'options' => array( 
                        'token' => 'mdp', 
                    ), 
                ), 

            ), 
        )));
        
        $inputFilter->add($factory->createInput(array(
                'name'     => 'rang',
                'required' => true, 
                'filters'  => array(
                     array('name' => 'StripTags'), 
                     array('name' => 'StringTrim'), 
                ),
            )));
        
         $this->inputFilter = $inputFilter;
                   
        }
        return $this->inputFilter;
    } 
}