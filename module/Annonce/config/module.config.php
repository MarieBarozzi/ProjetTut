<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Annonce\Controller\Annonce' => 'Annonce\Controller\AnnonceController',
        ),
    ),
    
	 'router' => array(
        'routes' => array(
            'annonce' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/annonce[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Annonce\Controller\Annonce',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
		
	
    'view_manager' => array(
        'template_path_stack' => array(
            'annonce' => __DIR__ . '/../view',
        ),
    ),
);