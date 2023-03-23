<?php

namespace Application\Controller;


use Application\Model\VerifyModel;
use Laminas\Mvc\Controller\AbstractActionController;


class VerifyController extends AbstractActionController
{
    private $model;
    
    
    public function __construct(VerifyModel $model)
    {
    	$this->model = $model;
    }
    
    
    public function indexAction()
    {
        try {
            $code = $this->params()->fromRoute('code');
            
            if ($this->model->authenticate($code) !== false) {
                $this->flashMessenger()->addSuccessMessage("Verification successful, you can now login.");
                
                return $this->redirect()->toRoute('home/verify', array('code' => $code, 'action' => 'success'));
            }
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage("Error processing your verification code, please try again. " . $e->getMessage());
            
            return $this->redirect()->toRoute('home/verify', array('code' => $code, 'action' => 'failure'));
        }
    }
    
    
    public function successAction()
    {
        
    }
    
    
    public function failureAction()
    {
        
    }
    
}