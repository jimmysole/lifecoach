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


    public function indexAction(): ViewModel
    {
        $this->viewModel->setVariable('boards', $this->model->displayBoards());

        return $this->viewModel;
    }


    public function getboardAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->viewModel->setTerminal(true);

        $id = intval($this->params()->fromPost('id'));

        echo "forum/view-board/$id";

        return $this->viewModel;
    }


    public function viewboardAction() : ViewModel
    {
        $id = intval($this->params()->fromRoute('id'));

        $this->viewModel->setVariable('board', $this->model->displayBoard($id));

        return $this->viewModel;
    }
}