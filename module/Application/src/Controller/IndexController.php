<?php

namespace Application\Controller;

use Application\Model\IndexModel;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;


class IndexController extends AbstractActionController
{

    private IndexModel $model;
    private ViewModel $view_model;

    public function __construct(IndexModel $model)
    {
        $this->model = $model;
        $this->view_model = new ViewModel();
    }

    public function indexAction() : ViewModel
    {

        if (count($this->model->listArticles(), 1) > 0) {
            $this->view_model->setVariable('articles', $this->model->listArticles());
        }


        return $this->view_model;
    }
}
