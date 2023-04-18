<?php

namespace Application\Classes;


use Application\Interfaces\ForumInterface;


class Forums implements ForumInterface
{
    public function displayBoards(): array
    {
        return [];
    }


    public function displayHotTopics(): array
    {
        return [];
    }
}