<?php

namespace Application\Interfaces;


interface ForumInterface
{
    public function displayBoards() : array;

    public function displayHotTopics() : array;

    public function displayBoardModerators() : array;

    public function subscribeToBoard(string $board, array $options) : bool;
}