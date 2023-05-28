<?php

namespace Application\Controller;


use Application\Model\SocialModel;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;


class SocialController extends AbstractActionController
{
    public SocialModel $social_model;

    public ViewModel $view_model;


    public function __construct(SocialModel $social_model)
    {
        $this->social_model = $social_model;
        $this->view_model = new ViewModel();
    }


    public function indexAction()
    {

    }


    public function viewonlineusersAction() : ViewModel
    {

        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);

        echo json_encode($this->social_model->viewOnlineUsers());

        return $this->view_model;
    }


    public function sendchatrequestAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);

        if ($this->request->isPost()) {
            $values = [];

            foreach (explode(", ", $this->params()->fromPost('info')) as $value) {
                $values[] = $value;
            }

            //var_dump($values);

            if (false !== $this->social_model->sendChatRequest($values[0], [ 'message' => $values[1]])) {
                echo "Chat request sent";
            }
        }

        return $this->view_model;
    }


    public function pendingchatrequestsAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);

        echo json_encode($this->social_model->viewOutgoingChatRequests());

        return $this->view_model;
    }


    public function incomingchatrequestsAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);

        echo json_encode($this->social_model->viewIncomingChatRequests());

        return $this->view_model;
    }


    public function currentchatsAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);

        echo json_encode($this->social_model->currentChats());

        return $this->view_model;
    }


    public function acceptchatrequestAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);

        if ($this->request->isPost()) {
            $keys = [ 'id', 'chat_accepted' ];

            $values = [];

            foreach (explode(", ", $this->params()->fromPost('info')) as $value) {
                $values[] = $value;
            }

            if (false !== $this->social_model->acceptChatRequest(array_combine($keys, $values))) {
                echo "Chat request accepted.";
            } else {
                echo "Error accept chat request.";
            }
        }

        return $this->view_model;
    }


    public function denychatrequestAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);

        if ($this->request->isPost()) {
            $keys = [ 'id', 'chat_denied' ];

            $values = [];

            foreach (explode(", ", $this->params()->fromPost('info')) as $value) {
                $values[] = $value;
            }

            if (false !== $this->social_model->denyChatRequest(array_combine($keys, $values))) {
                echo "Chat request denied";
            } else {
                echo "Error denying chat request.";
            }
        }

        return $this->view_model;
    }


    public function viewuserprofilesAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);

        if ($this->request->isPost()) {
            if (!empty($this->params()->fromPost('loc'))) {
                if (false !== $this->social_model->viewProfiles(['by_location' => true, 'by_username' => false, 'by_real_name' => false, 'location' => $this->params()->fromPost('loc')])) {
                    echo json_encode($this->social_model->viewProfiles(['by_location' => true, 'by_username' => false, 'by_real_name' => false, 'location' => $this->params()->fromPost('loc')]));
                }
            } else if (!empty($this->params()->fromPost('user'))) {
                if (false !== $this->social_model->viewProfiles(['by_location' => false, 'by_real_name' => false, 'by_username' => true, 'username' => $this->params()->fromPost('user')])) {
                    echo json_encode($this->social_model->viewProfiles(['by_location' => false, 'by_real_name' => false, 'by_username' => true, 'username' => $this->params()->fromPost('user')]));
                }
            } else if (!empty($this->params()->fromPost('name'))) {
                if (false !== $this->social_model->viewProfiles(['by_location' => false, 'by_username' => false, 'by_real_name' => true, 'real_name' => $this->params()->fromPost('name')])) {
                    echo json_encode($this->social_model->viewProfiles(['by_location' => false, 'by_username' => false, 'by_real_name' => true, 'real_name' => $this->params()->fromPost('name')]));
                }
            }
        }

        return $this->view_model;
    }


    public function viewboardsubscriptionsAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->view_model->setTerminal(true);


        if ($this->request->isPost()) {
            echo json_encode($this->social_model->viewBoardSubscriptions());
        }


        return $this->view_model;
    }
}
