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
}
