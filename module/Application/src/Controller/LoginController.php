<?php

namespace Application\Controller;



use Application\Model\Storage\LoginAuthStorage;
use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;

use Laminas\View\Model\ViewModel;

use Application\Model\LoginModel;

use Application\Form\LoginForm;
use Application\Model\Filters\Login;


class LoginController extends AbstractActionController
{
    public AuthenticationService $auth_service;
    public LoginAuthStorage $storage;
    
    private LoginModel $model;
    
    public function __construct(LoginModel $model, AuthenticationService $auth_service, LoginAuthStorage $storage)
    {
    	$this->model = $model;
    	$this->auth_service = $auth_service;
    	$this->storage = $storage;
    }
	
	public function indexAction()
    {
       if ($this->auth_service->hasIdentity()) {
           return $this->redirect()->toRoute('home/user');
       }
        
        $form = new LoginForm();
        
        return new ViewModel(array('form' => $form));
    }
    
    
    public function authAction()
    {
        $form = new LoginForm();
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $login = new Login();
            
            $form->setInputFilter($login->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $login->exchangeArray($form->getData());
                
                try {
	                if (!$this->model->handleLogin()->verifyCredentials($login)) {
		                $this->flashMessenger()->addErrorMessage('Invalid username and/or password');
	                }
	                
	                if (!$this->model->handleLogin()->checkSession(array('username' => $login->username))) {
		                $this->flashMessenger()->addErrorMessage('A session is already active with that username.');
	                }
                } catch (\Exception $e) {
	                return $this->redirect()->toRoute('home/login', array('action' => 'failure'));
                }
                
				try {
					$this->auth_service->getAdapter()
						->setIdentity($login->username)
						->setCredential($this->model->handleLogin()->verifyCredentials($login)['pass']);
				} catch (\Exception $e) {
					return $this->redirect()->toRoute('home/login', [ ' action' => 'failure' ]);
				}
            
                $result = $this->auth_service->authenticate();
                
                foreach ($result->getMessages() as $message) {
                    $this->flashMessenger()->addMessage($message);
                }
                
                if ($result->isValid()) {
                    if ($login->remember_me == 1) {
                        try {
                            $this->storage->rememberUser(1);
                            $this->auth_service->getStorage()->write($login->username);
                            
                            $this->model->handleLogin()->insertSession(array('username' => $login->username,
                                'password' => $this->model->handleLogin()->verifyCredentials($login)['pass'], 'session_id' => session_id()));
                        } catch (\Exception $e) {
                            return $this->redirect()->toRoute('home/login', array('action' => 'failure'));
                        }
                    } else if ($login->remember_me == 0) {
                        try {
                            $this->storage->rememberUser(0);
                            $this->auth_service->getStorage()->write($login->username);
                            
                            $this->model->handleLogin()->insertSession(array('username' => $login->username,
                                'password' => $this->model->handleLogin()->verifyCredentials($login)['pass'], 'session_id' => session_id()));
                        } catch (\Exception $e) {
                            return $this->redirect()->toRoute('home/login', array('action' => 'failure'));
                        }
                    }
                    
                    return $this->redirect()->toRoute('home/user');
                } else {
                    foreach ($result->getMessages() as $message) {
                        $this->flashMessenger()->addErrorMessage($message);
                    }
                    return $this->redirect()->toRoute('home/login', array('action' => 'failure'));
                }
            } else {
            	$this->flashMessenger()->addErrorMessage("Invalid form submitted, most likely due to invalid credentials or empty values.");
            	
            	return $this->redirect()->toRoute('home/login', array('action' => 'failure'));
            }
        }
    }
    
    
    public function failureAction()
    {
    	$this->layout('layout/failure');
    }
}