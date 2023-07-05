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
}
