<?php

namespace Application\Model;


use Application\Classes\Social;
use Application\Interfaces\SocialInterface;
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


    public function sendChatRequest(string $with, array $params) : SocialInterface|bool
    {
        return $this->social->sendChatRequest($with, $params);
    }


    public function viewOutgoingChatRequests() : array|bool
    {
        return $this->social->viewOutgoingChatRequests();
    }


    public function viewIncomingChatRequests() : array|bool
    {
        return $this->social->viewIncomingChatRequests();
    }


    public function currentChats() : array|bool
    {
        return $this->social->currentChats();
    }


    public function acceptChatRequest(array $params) : bool
    {
        return $this->social->acceptChatRequest($params);
    }
}