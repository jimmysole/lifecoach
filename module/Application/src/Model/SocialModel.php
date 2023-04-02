<?php

namespace Application\Model;


use Application\Classes\Social;
use Laminas\Db\TableGateway\TableGateway;


class SocialModel
{
    public TableGateway $gateway;

    protected string $user;


    public function __construct(TableGateway $gateway, string $user)
    {
        $this->gateway = $gateway;
        $this->user = $user;
    }
}