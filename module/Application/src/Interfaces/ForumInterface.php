<?php

namespace Application\Interfaces;


interface ForumInterface
{
    public function displayBoards() : array;

    public function displayHotTopics() : array;
}