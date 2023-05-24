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


    public function displayBoard(int $id) : array|bool
    {
        return $this->forum->displayBoard($id);
    }


    public function postTopic(int $board, string $topic, string $message, array $message_options = []) : bool
    {
        return $this->forum->postMessage($board, $topic, $message, $message_options);
    }


    public function subscribeToBoard(int $board_id, array $options) : bool
    {
        return $this->forum->subscribeToBoard($board_id, $options);
    }
}