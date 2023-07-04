<?php

namespace Application;


use Application\Controller\CategoriesController;
use Application\Controller\ForumController;
use Application\Controller\LoginController;
use Application\Controller\SocialController;
use Application\Controller\UserController;
use Application\Model\ArticleModel;
use Application\Model\ContactModel;
use Application\Model\Filters\Login;
use Application\Model\Filters\Register;
use Application\Model\ForumModel;
use Application\Model\IndexModel;
use Application\Model\LoginModel;
use Application\Model\LogoutModel;
use Application\Model\ProfileModel;
use Application\Model\RegisterModel;
use Application\Model\SocialModel;
use Application\Model\Storage\LoginAuthStorage;
use Application\Model\UserModel;
use Application\Model\VerifyModel;

use Laminas\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as DbTableAuthAdapter;
use Laminas\Authentication\AuthenticationService;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Log\Logger;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\ModuleManager\ModuleManager;
use Laminas\Mvc\Application;
use Laminas\Mvc\ModuleRouteListener;
use Laminas\Mvc\MvcEvent;
use Laminas\Session\SessionManager;
use Laminas\View\Model\ViewModel;
use Laminas\View\Renderer\PhpRenderer;


class Module implements ConfigProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
    	try {
		    $eventManager        = $e->getApplication()->getEventManager();
		    $moduleRouteListener = new ModuleRouteListener();
		    $moduleRouteListener->attach($eventManager);
		
		    $sm = $e->getApplication()->getServiceManager();
		
		    $this->startSession($e);
		
		    $db = $sm->get(Adapter::class);
		
		    if ($db->getDriver()->getConnection()->connect()->isConnected()) {
			    $check = $db->getDriver()->getConnection()->execute("SHOW TABLES LIKE 'errors'");
			
			    if ($check->count() > 0) {
				    $shared_mgr = $eventManager->getSharedManager();
				
				    $shared_mgr->attach(Application::class, 'dispatch.error', function($e) use ($sm) {
					    if ($e->getParam('exception')) {
						    $sm->get(Logger::class)->crit($e->getParam('exception'));
					    }
				    });
			    }
		    }
		
		    $eventManager->attach('dispatch', [ $this, 'getCategories' ]);
		    $eventManager->attach('render', [ $this, 'getIdentity' ]);
	    } catch (\Exception $ex) {
		    $target = $e->getTarget();
		    $service_mgr = $target->getServiceManager();


		    $view_model = $e->getViewModel();
		    $view_model->setTemplate('layout/dberror');
		
		    $content = new ViewModel();
		    $content->setTemplate('error/dberrorpage');
		
		    $view_model->setVariable('content', $service_mgr->get(PhpRenderer::class)
			    ->render($content));
		
		    echo $service_mgr->get(PhpRenderer::class)->render($view_model);
		
		    $e->stopPropagation();
	    }
     
    }
    
    
    public function init(ModuleManager $manager)
    {
        $event = $manager->getEventManager();
        
        $shared_event = $event->getSharedManager();
        
        $shared_event->attach(__NAMESPACE__, 'dispatch', function($e) {
            $controller = $e->getTarget();
            
            if (get_class($controller) == CategoriesController::class) {
                $controller->layout('layout/categories');
            } else if (get_class($controller) == LoginController::class) {
                $controller->layout('layout/login');
            } else if (get_class($controller) == UserController::class) {
                $controller->layout('layout/user');
            } else if (get_class($controller) == SocialController::class) {
                $controller->layout('layout/user');
            } else if (get_class($controller) == ForumController::class) {
                $controller->layout('layout/forum');
            }
        }, 100);
    }
    

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

   
    public function getCategories(MvcEvent $e)
    {
        $controller = $e->getTarget();
        
		try {
			$controller->layout()->articles = (new IndexModel(new TableGateway('articles', $e->getApplication()->getServiceManager()->get(Adapter::class))))->listArticles();
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
    }
    
    
    public function getIdentity(MvcEvent $e)
    {
        $controller = $e->getViewModel();


	    if ($e->getApplication()->getServiceManager()->get(AuthenticationService::class)->hasIdentity()) {
            $controller->setVariable('user', $e->getApplication()->getServiceManager()->get(AuthenticationService::class)->getIdentity());
        }
    }
    
    
    public function startSession(MvcEvent $em)
    {
        $session = $em->getApplication()->getServiceManager()->get(SessionManager::class);
        
        $session->start();
    }
    

    public function getServiceConfig(): array
    {
        return array(
            'factories' => array(
                RegisterModel::class => function($container) {
                    $table_gateway = $container->get(RegisterTableGateway::class);
                    return new RegisterModel($table_gateway);
                },
            
                RegisterTableGateway::class => function($container) {
                    $db_adapter = $container->get(AdapterInterface::class);
                    $result_set_prototype = new ResultSet();
                    $result_set_prototype->setArrayObjectPrototype(new Register());
                    return new TableGateway('pending_users', $db_adapter, null, $result_set_prototype);
                },
                
                VerifyModel::class => function($container) {
                    $table_gateway = $container->get(VerifyTableGateway::class);
                    return new VerifyModel($table_gateway);
                },
                
                VerifyTableGateway::class => function($container) {
                    $db_adapter = $container->get(AdapterInterface::class);
                    return new TableGateway('pending_users', $db_adapter);
                },
                
                LoginModel::class => function($container) {
                    $table_gateway = $container->get(LoginTableGateway::class);
                    return new LoginModel($table_gateway);
                },
                
                LoginTableGateway::class => function($container) {
                    $db_adapter = $container->get(AdapterInterface::class);
                    $result_set_prototype = new ResultSet();
                    $result_set_prototype->setArrayObjectPrototype(new Login());
                    return new TableGateway('sessions', $db_adapter, null, $result_set_prototype);
                },
                
                 LoginAuthStorage::class => function() {
                    return new LoginAuthStorage('kb');
                },
	            
	            
                AuthenticationService::class => function($container) {
                	$db_adapter = $container->get(Adapter::class);
                    $auth_adapter = new DbTableAuthAdapter($db_adapter, 'users', 'username', 'password');

                    $auth_service = new AuthenticationService();
                    $auth_service->setAdapter($auth_adapter);
                    $auth_service->setStorage($container->get(LoginAuthStorage::class));
                    
                    return $auth_service;
                },
	            
                
                LogoutModel::class => function($container) {
                    $table_gateway = $container->get(LogoutService::class);
                    return new LogoutModel($table_gateway);
                },
                
                LogoutService::class => function($container) {
                    $db_adapter = $container->get(AdapterInterface::class);
                    return new TableGateway('sessions', $db_adapter);
                },
	            
	            UserModel::class => function($container) {
                	$table_gateway = $container->get(UserService::class);
                	return new UserModel($table_gateway, $container->get(AuthenticationService::class)->getIdentity());
	            },
	            
	            UserService::class => function($container) {
                	$db_adapter = $container->get(AdapterInterface::class);
                	return new TableGateway('users', $db_adapter);
	            },
	            
	            ArticleModel::class => function ($container) {
                	$table_gateway = $container->get(ArticleService::class);
                	return new ArticleModel($table_gateway);
	            },
	            
	            ArticleService::class => function ($container) {
                	$db_adapter = $container->get(AdapterInterface::class);
                	return new TableGateway('articles', $db_adapter);
	            },
	            
	            ContactService::class => function ($container) {
					$db_adapter = $container->get(AdapterInterface::class);
					return new TableGateway('messages', $db_adapter);
	            },
	            
	            ContactModel::class => function ($container) {
					$table_gateway = $container->get(ContactService::class);
					return new ContactModel($table_gateway);
	            },

                IndexModel::class => function($container) {
                    $table_gateway = $container->get(IndexService::class);
                    return new IndexModel($table_gateway);
                },

                IndexService::class => function ($container) {
                    $db_adapter = $container->get(AdapterInterface::class);
                    return new TableGateway('articles', $db_adapter);
                },

                SocialModel::class => function($container) {
                    $table_gateway = $container->get(SocialService::class);
                    return new SocialModel($table_gateway, $container->get(AuthenticationService::class)->getIdentity());
                },

                SocialService::class => function ($container) {
                    $db_adapter = $container->get(AdapterInterface::class);
                    return new TableGateway('chats', $db_adapter);
                },

                ForumModel::class => function ($container) {
                    $table_gateway = $container->get(ForumService::class);
                    return new ForumModel($table_gateway, $container->get(AuthenticationService::class)->getIdentity());
                },

                ForumService::class => function ($container) {
                    $db_adapter = $container->get(AdapterInterface::class);
                    return new TableGateway('boards', $db_adapter);
                },

                ProfileModel::class => function ($container) {
                    $table_gateway = $container->get(ProfileService::class);
                    return new ProfileModel($table_gateway, $container->get(AuthenticationService::class)->getIdentity());
                },

                ProfileService::class => function ($container) {
                    $db_adapter = $container->get(AdapterInterface::class);
                    return new TableGateway('profile', $db_adapter);
                }
            )
        );
    } 
}
