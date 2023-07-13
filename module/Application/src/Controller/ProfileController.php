<?php

namespace Application\Controller;


use Application\Model\ProfileModel;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ProfileController extends AbstractActionController
{
    protected ProfileModel $profile_model;

    protected ViewModel $viewModel;

    public function __construct(ProfileModel $model)
    {
        $this->profile_model = $model;
        $this->viewModel = new ViewModel();
    }


    public function indexAction()
    {

    }


    public function makeprofileAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->viewModel->setTerminal(true);

        if ($this->request->isPost()) {
            if (false !== $this->profile_model->createProfile(['real_name' => $this->params()->fromPost('profileRealName'),
                    'location' => $this->params()->fromPost('profileLocation'),
                    'avatar' => $this->params()->fromPost('profileAvatar'), 'bio' => $this->params()->fromPost('profileBio')])) {
                echo "Profile created";
            } else {
                echo "Error creating profile, please try again.";
            }
        }

        return $this->viewModel;
    }

    public function uploadprofileavatarAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->viewModel->setTerminal(true);

        if ($this->request->isPost()) {
            if (false !== $this->profile_model->uploadAvatarImage($this->params()->fromFiles())) {
                echo "Avatar uploaded";
            } else {
                echo "Error uploading avatar";
            }
        }

        return $this->viewModel;
    }


    public function getprofileAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->viewModel->setTerminal(true);

        echo json_encode($this->profile_model->getProfile());

        return $this->viewModel;
    }


    public function editprofileAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->viewModel->setTerminal(true);

        if ($this->request->isPost()) {
            if (false !== $this->profile_model->editProfile(['real_name' => $this->params()->fromPost('realName'),
                    'location' => $this->params()->fromPost('location'),
                    'avatar'   => $this->params()->fromPost('avatar'),
                    'bio'      => $this->params()->fromPost('bio')])) {
                echo "Profile updated";
            } else {
                echo "Error updating profile, please try again.";
            }
        }

        return $this->viewModel;
    }


    public function deleteprofileAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->viewModel->setTerminal(true);

        if ($this->request->isPost()) {
            if (false !== $this->profile_model->deleteProfile()) {
                echo "Profile deleted.";
            } else {
                echo "Error deleting profile, please try again.";
            }
        }

        return $this->viewModel;
    }
}
