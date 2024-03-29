<?php

namespace Application\Model;

use Application\Interfaces\ConferenceInterface;
use Application\Classes\Conference;

use Exception;
use Laminas\Db\TableGateway\TableGateway;




class ConferenceModel
{
    private TableGateway $tableGateway;

    private Conference $conference;

    protected string $user;


    public function __construct(TableGateway $gateway, string $user)
    {
        $this->tableGateway = $gateway;
        $this->user = $user;
        $this->conference = new Conference($this->tableGateway, $user);
    }

    /**
     * @throws Exception
     */
    public function getConference() : array|bool
    {
        return $this->conference->getConferences();
    }

    /**
     * @throws Exception
     */
    public function startConference(): ConferenceInterface
    {
        return $this->conference->startConference();
    }


    /**
     * @throws Exception
     */
    public function endConference() : ConferenceInterface
    {
        return $this->conference->endConference();
    }


    /**
     * @throws Exception
     */
    public function prepareRoom(array $details) : ConferenceInterface
    {
        return $this->conference->prepareRoom($details);
    }
}