<?php


declare(strict_types=1);

namespace Application;


use Application\Controller\ArticlesController;
use Application\Controller\CategoriesController;
use Application\Controller\ContactController;
use Application\Controller\IndexController;
use Application\Controller\LoginController;
use Application\Controller\LogoutController;
use Application\Controller\RegisterController;
use Application\Controller\SocialController;
use Application\Controller\UserController;
use Application\Controller\VerifyController;
use Application\Model\Storage\LoginAuthServiceGateway;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;


return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action'     => 'index',
                    ],
                ],
	
	            'may_terminate' => true,
	            'child_routes' => [
		            'default' => [
			            'type'    => 'Segment',
			            'options' => [
				            'route'    => '/[:controller[/:action]]',
				            'constraints' => [
					            'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
					            'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
				            ],
				            'defaults' => [],
			            ],
		            ],

		            
		            'contact' => [
						'type' => 'Literal',
			            'options' => [
							'route' => 'contact',
				            'defaults' => [
								'controller' => ContactController::class,
					            'action' => 'index',
				            ],
			            ],
		            ],
		
		            'logout' => [
			            'type' => 'Literal',
			            'options' => [
			            	'route' => 'logout',
				            'defaults' => [
					            'controller' => LogoutController::class,
					            'action'     => 'index',
				            ],
			            ],
		            ],
		            
		            'register' => [
		            	'type' => 'Segment',
			            'options' => [
				            'route' => 'register[/:action]',
				            'defaults' => [
					            'controller' => RegisterController::class,
					            'action'     => 'index',
				            ],
			            ],
		            ],
		            
		            'verify' => [
		            	'type'=> 'Segment',
			            'options' => [
			                'route' => 'verify/:code[/:action]',
				            'constraints' => [
				                'code' => '[a-zA-Z0-9][a-zA-Z0-9_-]*',
					            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
				            ],
				            
				            'defaults' => [
				            	'controller' => VerifyController::class,
					            'action' => 'index'
				            ],
			            ],
		            ],
		
		            'login' => [
		            	'type' => 'Segment',
			            'options' => [
				            'route' => 'login[/:action]',
				            'defaults' => [
					            'controller' => LoginController::class,
					            'action'     => 'index',
				            ],
			            ],
		            ],
		
		            'logout' => [
			            'type' => 'Literal',
			            'options' => [
				            'route' => 'logout',
				            'defaults' => [
                                'controller' => LogoutController::class,
					            'action'     => 'index',
				            ],
			            ],
		            ],
		            
		            'articles' => [
		            	'type' => 'Segment',
			            'options' => [
			            	'route' => 'articles[/:article_id]',
				            'defaults' => [
				            	'controller' => ArticlesController::class,
					            'action'      => 'index',
				            ],
				            
				            'constraints' => [
								'article_id' => '\d+',
				            ],
			            ],
		            ],
		
		            'user' => [
			            'type' => 'Segment',
			            'options' => [
				            'route' => 'user[/:action][/:id]',
				            'defaults' => [
					            'controller' => UserController::class,
					            'action'     => 'index',
				            ],
			            ],
		            ],

                    'social' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => 'social[/:action][/:id]',
                            'defaults' => [
                                'controller' => SocialController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                ],
            ],
	           
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

	'controllers' => [
		'factories' => [
			Controller\IndexController::class => ReflectionBasedAbstractFactory::class,
			Controller\LoginController::class => ReflectionBasedAbstractFactory::class,
			Controller\UserController::class => ReflectionBasedAbstractFactory::class,
			Controller\LogoutController::class => ReflectionBasedAbstractFactory::class,
			Controller\CategoriesController::class => ReflectionBasedAbstractFactory::class,
			Controller\ArticlesController::class => ReflectionBasedAbstractFactory::class,
			Controller\ContactController::class => ReflectionBasedAbstractFactory::class,
            Controller\VerifyController::class => ReflectionBasedAbstractFactory::class,
            Controller\SocialController::class => ReflectionBasedAbstractFactory::class,
		],
	],
	
	'service_manager' => [
		'abstract_factories' => [
		
		],
	],
	
	
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
