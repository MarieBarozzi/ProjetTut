<?php
namespace Annonce; 
use Annonce\Model\Annonce;
use Annonce\Model\AnnonceTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Annonce\Model\Departement;
use Annonce\Model\DepartementTable;
use Annonce\Model\Categorie;
use Annonce\Model\CategorieTable;



class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Annonce\Model\AnnonceTable' =>  function($sm) {
                    $tableGateway = $sm->get('AnnonceTableGateway');
                    $table = new AnnonceTable($tableGateway);
                    return $table;
                },
                'AnnonceTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Annonce());
                    return new TableGateway('annonce', $dbAdapter, null, $resultSetPrototype);
                },
                
                'Annonce\Model\DepartementTable' =>  function($sm) {
                    $tableGateway = $sm->get('DepartementTableGateway');
                    $table = new DepartementTable($tableGateway);
                    return $table;
                },
                'DepartementTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Departement());
                    return new TableGateway('departement', $dbAdapter, null, $resultSetPrototype);
                },
                
                 'Annonce\Model\CategorieTable' =>  function($sm) {
                    $tableGateway = $sm->get('CategorieTableGateway');
                    $table = new CategorieTable($tableGateway);
                    return $table;
                },
                'CategorieTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Categorie());
                    return new TableGateway('categorie', $dbAdapter, null, $resultSetPrototype);
                }
             )  
        );
    }
}