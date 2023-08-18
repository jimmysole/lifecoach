<?php

namespace Application\Model;

use Application\Interfaces\ConferenceInterface;
use Laminas\Db\TableGateway\TableGateway;

use Application\Classes\Conference;


class ConferenceModel
{
    private TableGateway $tableGateway;

    private Conference $conference;

    protected string $user;


    public function __construct(TableGateway $gateway, string $user)
    {
        $this->tableGateway = $gateway;
        $this->user = $user;
        $this->conference = new Conference($gateway, $user);
    }


    public function startConference(): ConferenceInterface
    {
        return $this->conference->startConference();
    }


    public function endConference() : ConferenceInterface
    {
        return $this->conference->endConference();
    }


    public function prepareRoom(array $details) : ConferenceInterface
    {
        return $this->conference->prepareRoom($details);
    }
}