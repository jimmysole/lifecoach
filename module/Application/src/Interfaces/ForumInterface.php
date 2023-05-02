<?php

namespace Application\Interfaces;


interface ForumInterface
{
    public function displayBoards() : array;

    public function displayHotTopics() : array;

    public function displayBoardModerators() : array;

    public function subscribeToBoard(string $board, array $options) : bool;

    public function postMessage(string $board, string $topic, string $message, array $message_options = []) : bool;

    public function editMessage(string $board, int $message_id, array $edits) : bool;
}