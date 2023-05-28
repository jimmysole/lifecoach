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
        $user = $_SESSION['kb'];

        $status = [];

        foreach ($user as $value) {
            $status[] = $value;
        }


        $id = intval($this->params()->fromRoute('id'));

        $this->viewModel->setVariable('board', $this->model->displayBoard($id));
        $this->viewModel->setVariable('user', $status[0]);

        return $this->viewModel;
    }


    public function posttopicAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->viewModel->setTerminal(true);

        $board_id    = intval($this->params()->fromPost('boardId'));
        $board_topic = $this->params()->fromPost('boardTopic');
        $board_msg   = $this->params()->fromPost('boardMessage');
        $subscribe   = $this->params()->fromPost('boardSubscribe') === "true" ? 1 : false;

        if ($this->model->postTopic($board_id, $board_topic, $board_msg, [ 'subscribe_to_post' => $subscribe ])) {
            echo "Topic was posted to the board";
        } else {
           echo "Error posting the topic to the board";
        }

        return $this->viewModel;
    }


    public function gettopicsAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->viewModel->setTerminal(true);

        if ($this->request->isPost()) {
            echo json_encode($this->model->displayBoard($this->params()->fromPost('boardID')));
        }


        return $this->viewModel;
    }


    public function subscribetoboardAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->viewModel->setTerminal(true);

        $board_id = intval($this->params()->fromPost('boardID'));
        $board_options = intval($this->params()->fromPost('boardOptions'));


        if ($this->model->subscribeToBoard($board_id, [ 'notify' => $board_options ])) {
            echo "Subscription to this board successful";
        } else {
            echo "Error subscribing to the board.";
        }

        return $this->viewModel;
    }
}