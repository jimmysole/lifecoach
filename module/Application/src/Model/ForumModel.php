<?php

namespace Application\Model;


use Application\Classes\Forums;
use Laminas\Db\TableGateway\TableGateway;

class ForumModel
{
    public TableGateway $gateway;

    protected string $user;

    private Forums $forum;


    public function __construct(TableGateway $gateway, string $user)
    {
        $this->gateway = $gateway;
        $this->user = $user;
        $this->forum = new Forums($this->gateway, $this->user);
    }


    public function displayBoards() : array
    {
        return $this->forum->displayBoards();
    }
}