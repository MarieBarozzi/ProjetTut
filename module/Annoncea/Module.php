<?php
namespace Annoncea; 
use Annoncea\Model\Annonce;
use Annoncea\Model\AnnonceTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Annoncea\Model\Departement;
use Annoncea\Model\DepartementTable;
use Annoncea\Model\Categorie;
use Annoncea\Model\CategorieTable;
use Annoncea\Model\Utilisateur;
use Annoncea\Model\UtilisateurTable;
use Annoncea\Model\Photo;
use Annoncea\Model\PhotoTable;
use Annoncea\Model\Favoris;
use Annoncea\Model\FavorisTable;
use Annoncea\Model\Region;
use Annoncea\Model\RegionTable;
use Annoncea\Model\Message;
use Annoncea\Model\MessageTable;
use Zend\Authentication\Adapter\DbTable;




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
        return array(/*L’objectif du modèle de conception Fabrique est de fournir un objet prêt à l’emploi, configuré correctement, en libérant le code client de toute responsabilité (choix de l’implémentation, configuration, instanciation, …).*/
            'factories' => array(
                'Annoncea\Model\AnnonceTable' =>  function($sm) {
                    $tableGateway = $sm->get('AnnonceTableGateway');
                    $table = new AnnonceTable($tableGateway);
                    return $table; /*retourne un objet correspondant à la table Annonce*/
                },
                'AnnonceTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');/*fait le lien avec ma bd*/
                    $resultSetPrototype = new ResultSet();/*sert à modéliser les résultats d'une requete*/
                    $resultSetPrototype->setArrayObjectPrototype(new Annonce());/*transforme le resultset générique en resultset de Annonce*/
                    return new TableGateway('annonce', $dbAdapter, null, $resultSetPrototype);/*crée une passerelle vers la table = les methodes d'interaction avec la table sont appelées sur cet objet*/
                },
                'Annoncea\Model\DepartementTable' =>  function($sm) {
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
                
                 'Annoncea\Model\CategorieTable' =>  function($sm) {
                    $tableGateway = $sm->get('CategorieTableGateway');
                    $table = new CategorieTable($tableGateway);
                    return $table;
                },
                'CategorieTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Categorie());
                    return new TableGateway('categorie', $dbAdapter, null, $resultSetPrototype);
                }, 
                
                'Annoncea\Model\UtilisateurTable' =>  function($sm) {
                    $tableGateway = $sm->get('UtilisateurTableGateway');
                    $table = new UtilisateurTable($tableGateway);
                    return $table;
                },
                  'UtilisateurTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Utilisateur());
                    return new TableGateway('utilisateur', $dbAdapter, null, $resultSetPrototype);
                }, 
                
                'Annoncea\Model\PhotoTable' =>  function($sm) {
                    $tableGateway = $sm->get('PhotoTableGateway');
                    $table = new PhotoTable($tableGateway);
                    return $table;
                },
                  'PhotoTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Photo());
                    return new TableGateway('photo', $dbAdapter, null, $resultSetPrototype);
                }, 
                
                'Annoncea\Model\FavorisTable' =>  function($sm) {
                    $tableGateway = $sm->get('FavorisTableGateway');
                    $table = new FavorisTable($tableGateway);
                    return $table;
                },
                  'FavorisTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Favoris());
                    return new TableGateway('favoris', $dbAdapter, null, $resultSetPrototype);
                }, 
                
                
                 'Annoncea\Model\RegionTable' =>  function($sm) {
                    $tableGateway = $sm->get('RegionTableGateway');
                    $table = new RegionTable($tableGateway);
                    return $table;
                },
                  'RegionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Region());
                    return new TableGateway('region', $dbAdapter, null, $resultSetPrototype);
                }, 

                'Annoncea\Model\MessageTable' => function ($sm) {
                    $tableGateway = $sm->get('MessageTableGateway');
                    $table = new MessageTable($tableGateway);
                    return $table; 
                },
                    'MessageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new message());
                    return new TableGateway('message', $dbAdapter, null, $resultSetPrototype);
                },                 
                
                
                
                'AuthAdapter' => function($sm){
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     return new DbTable($dbAdapter,'utilisateur','mail','mdp');
                 }
                
                ) 
        );
    }
}