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
            /*
             * une route commencant par annonce sera eventuellement suivie de l'action puis d'un id
             * le controleur appelé est celui correspondant a la clé Annoncea\Controller\Annonce
             * l'action par defaut est index
             * une action "toto" appelle la fonction "totoAction" et correspond a la vue toto.phtml
             */
            'annonce' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/annonce[/][:action][/:id][/page/:page]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'page'   => '[0-9]+',
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
                    'route'    => '/utilisateur[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Annoncea\Controller\Utilisateur',
                        'action'     => 'index',
                    ),
                ),
            ),
            
        ),
    ),
		
	/*localise les vues*/
    'view_manager' => array(
        'template_path_stack' => array(
            'annonce' => __DIR__ . '/../view',
        ),
    ),
);