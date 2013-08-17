<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Annoncea\Controller\Annonce' => 'Annoncea\Controller\AnnonceController',
            'Annoncea\Controller\Utilisateur' => 'Annoncea\Controller\UtilisateurController',
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
                        'controller' => 'Annoncea\Controller\Annonce',
                        'action'     => 'index',
                    ),
                ),
            ),
            'utilisateur' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/utilisateur[/][:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Annoncea\Controller\Utilisateur',
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