<?php

namespace Application\Model;


use Application\Classes\Social;
use Laminas\Db\TableGateway\TableGateway;


class SocialModel
{
    public TableGateway $gateway;

    protected string $user;

    private Social $social;

    public function __construct(TableGateway $gateway, string $user)
    {
        $this->gateway = $gateway;
        $this->user = $user;
        $this->social = new Social($this->gateway, $this->user);
    }


    public function viewOnlineUsers() : array|bool
    {
        return $this->social->viewOnlineUsers();
    }
}