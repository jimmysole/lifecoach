<?php

namespace Application\Controller;


use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

use Application\Model\RegisterModel;
use Application\Form\RegisterForm;
use Application\Model\Filters\Register;


class RegisterController extends AbstractActionController
{
    public $register;
    
    private $model;
    
    public function __construct(RegisterModel $model)
    {
    	$this->model = $model;
    }
    
    
    public function indexAction()
    {
        $form = new RegisterForm();
        
        return new ViewModel(array(
            'form' => $form
        ));
    }
    
    public function regAction()
    {
        $form = new RegisterForm();
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $register = new Register();
            
            $form->setInputFilter($register->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $register->exchangeArray($form->getData());
                
                if ($this->model->handleRegistration($register) !== false) {
                    $this->flashMessenger()->addSuccessMessage("Registration Successful! Please check your email address provided for a verification link to finish the registration process.");
                    
                    return $this->redirect()->toUrl('success');
                } else {
                    $this->flashMessenger()->addErrorMessage("Oops! Something went wrong while attempting to complete the registration process, please try again.");
                    
                    return $this->redirect()->toUrl('failure');
                }
            } else {
                $this->flashMessenger()->addErrorMessage("Oops! Something went wrong while attempting to complete the registration process, please try again. 2");
                
                return $this->redirect()->toUrl('failure');
            }
        }
    }
    
    public function successAction()
    {
        return;
    }
    
    
    public function failureAction()
    {
        return;
    }
}