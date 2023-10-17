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
}
