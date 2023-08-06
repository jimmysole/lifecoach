<?php

namespace Application\Controller;



use Application\Model\UserModel;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;


class UserController extends AbstractActionController
{
    public UserModel $user_service;

    public ViewModel $view_model;

    public function __construct(UserModel $user_model)
    {
        $this->user_service = $user_model;
        $this->view_model = new ViewModel();
    }


    public function indexAction() : ViewModel
    {
        $user = $_SESSION['kb'];

        $status = [];

        foreach ($user as $value) {
            $status[] = $value;
        }

        if ($this->user_service->checkIfAdmin($status[0])) {
            $this->view_model->setVariable('admin', 1);
        }



        $this->view_model->setVariable('full_name', function() {
            foreach ($this->user_service->getFullName() as $val) {
                echo $val['first_name'] . " " . $val['last_name'];
            }
        });

        return $this->view_model;
    }





    /////////////////////////////////////////////////////////////
    // admin actions
    public function viewrequestedmeetingsAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);

        echo json_encode($this->user_service->getRequestedMeetings());

        return $this->view_model;
    }


    public function viewconfirmedmeetingsAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);

        echo json_encode($this->user_service->getConfirmedMeetings());

        return $this->view_model;
    }


    public function confirmmeetingAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);

        if ($this->request->isPost()) {
            $keys = ['id', 'client', 'counselor', 'message', 'submitted_date', 'meeting_time', 'duration'];

            $values = [];

            foreach (explode(", ", $this->params()->fromPost('info')) as $value) {
                $values[] = $value;
            }

            if (false !== $this->user_service->confirmMeeting(array_combine($keys, $values))) {
                echo "Meeting confirmed.";
            } else {
                echo "Error confirming meeting.";
            }
        }

        return $this->view_model;
    }


    public function cancelmeetingAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);

        if ($this->request->isPost()) {
            $keys = [ 'id' ];

            $values = [];

            foreach (explode(", ", $this->params()->fromPost('info')) as $value) {
                $values[] = $value;
            }

            if (false !== $this->user_service->cancelMeeting(array_combine($keys, $values))) {
                echo "Meeting cancelled";
            } else {
                echo "Error cancelling meeting";
            }
        }

        return $this->view_model;
    }


    public function reschedulemeetingAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);

        if ($this->request->isPost()) {
            $keys = [ 'id', 'approved_date', 'duration'];

            $values = [];

            foreach (explode(", ", $this->params()->fromPost('info')) as $value) {
                $values[] = $value;
            }

            if (false !== $this->user_service->rescheduleMeeting(array_combine($keys, $values))) {
                echo "Meeting rescheduled.";
            } else {
                echo "Error rescheduling meeting.";
            }
        }

        return $this->view_model;
    }


    public function startmeetingAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);

        if ($this->request->isPost()) {
            $keys = [ 'id' ];

            $values = [];

            foreach (explode(", ", $this->params()->fromPost('info')) as $value) {
                $values[] = $value;
            }

            if (false !== $this->user_service->startMeeting(array_combine($keys, $values))) {
                echo "Meeting was started";
            } else {
                echo "Error starting meeting";
            }
        }

        return $this->view_model;
    }


    public function postarticleAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);

        if ($this->request->isPost()) {
            if (false !== $this->user_service->postArticle($this->params()->fromPost('articleSubject'),
                $this->params()->fromPost('articleTitle'), $this->params()->fromPost('articleBody'),
                $this->params()->fromPost('articleFile'))) {
                echo "Article was posted!";
            } else {
                echo "Error posting article, please try again";
            }
        }

        return $this->view_model;
    }


    public function uploadarticleimageAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);

        if ($this->request->isPost()) {
            if (false !== $this->user_service->uploadArticleImage($this->params()->fromFiles())) {
                echo "Image uploaded";
            } else {
                echo "Error uploading the image";
            }
        }

        return $this->view_model;
    }


    public function viewarticlesAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);

        echo json_encode($this->user_service->viewArticles());

        return $this->view_model;
    }


    public function removearticleAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);

        if ($this->request->isPost()) {
            if (false !== $this->user_service->removeArticle(intval($this->params()->fromPost('info')))) {
                echo "Article was removed";
            } else {
                echo "Error removing article, please try again";
            }
        }

        return $this->view_model;
    }


    public function editarticleAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);

        if ($this->request->isPost()) {
            if (false !== $this->user_service->editArticle($this->params()->fromPost('articleId'),
                [ 'title' => $this->params()->fromPost('articleTitle'), 'subject' => $this->params()->fromPost('articleSubject'), 'body' => $this->params()->fromPost('articleBody') ])) {
                echo "Article was edited successfully.";
            } else {
                echo "Error editing article, please try again";
            }
        }

        return $this->view_model;
    }


    public function viewusersAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);

        echo json_encode($this->user_service->viewUserList());

        return $this->view_model;
    }


    public function viewscheduleAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);

        echo json_encode($this->user_service->upcomingSchedule());

        return $this->view_model;
    }
}