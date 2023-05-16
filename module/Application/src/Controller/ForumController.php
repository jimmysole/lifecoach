<?php


namespace Application\Controller;


use Application\Model\ForumModel;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ForumController extends AbstractActionController
{
    public ForumModel $model;

    public ViewModel $viewModel;


    public function __construct(ForumModel $model)   
    {
        $this->model = $model;
        $this->viewModel = new ViewModel();
    }


    public function indexAction()
    {
        $this->viewModel->setVariable('boards', $this->model->displayBoards());

        return $this->viewModel;
    }
}