<?php

namespace Application\Controller;


use Application\Model\LogoutModel;
use Application\Model\Storage\LoginAuthStorage;
use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;


class LogoutController extends AbstractActionController
{
    protected AuthenticationService $auth_service;
    protected LoginAuthStorage $storage;
    
    protected LogoutModel $logout_model;
    
    public function __construct(LogoutModel $logout_model, AuthenticationService $auth_service, LoginAuthStorage $auth_storage)
    {
    	$this->logout_model = $logout_model;
    	$this->auth_service = $auth_service;
    	$this->storage = $auth_storage;
    }
    
    
    public function indexAction()
    {
        $identity = $this->auth_service->getIdentity();
        
        if (!$identity) {
            return $this->redirect()->toRoute('home/login', array('action' => 'index'));
        } else {
            $this->logout_model->handleLogout()->deleteSession($identity);
            
            $this->storage->forgetUser();
            $this->auth_service->clearIdentity();
            
            return $this->redirect()->toRoute('home/login', array('action' => 'index'));
        }
    }
}