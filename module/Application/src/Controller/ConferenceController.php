<?php

namespace Application\Controller;


use Application\Model\ConferenceModel;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ConferenceController extends AbstractActionController
{
    public ConferenceModel $model;

    public ViewModel $viewModel;


    public function __construct(ConferenceModel $model)
    {
        $this->model = $model;
        $this->viewModel = new ViewModel();
    }


    public function indexAction() : ViewModel
    {

        return $this->viewModel;
    }


    public function startconferenceAction() : ViewModel
    {
        return $this->viewModel;
    }


    public function viewconferencesAction() : ViewModel
    {
        $this->layout()->setTerminal(true);
        $this->viewModel->setTerminal(true);

        try {
            echo json_encode($this->model->getConference());
        } catch (\Exception $e) {
            echo json_encode($e->getMessage());
        }

        return $this->viewModel;
    }


    public function cancelconferenceAction() : ViewModel
    {
        return $this->viewModel;
    }
}
