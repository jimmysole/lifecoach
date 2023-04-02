<?php


namespace Application\Classes;


use Application\Interfaces\SocialInterface;
use Laminas\Db\TableGateway\TableGateway;


class Social implements SocialInterface
{
    private TableGateway $gateway;

    private string $user;

    public function __construct(TableGateway $gateway, string $user)
    {
        $this->gateway = $gateway;
        $this->user = $user;
    }


    public function chat(string $with, array $params): SocialInterface|bool
    {

        return $this;
    }
}
