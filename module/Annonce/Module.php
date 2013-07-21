<?php
namespace Annonce; 

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
            ),
        );
    }
}
	
	
	
}