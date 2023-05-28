<?php

namespace Application\Interfaces;


interface ForumInterface
{
    public function displayBoards() : array;

    public function displayBoard(int $id) : array|bool;

    public function displayHotTopics() : array;

    public function displayBoardModerators() : array;

    public function subscribeToBoard(int $board_id, array $options) : bool;

    public function postMessage(int $board_id, string $topic, string $message, array $message_options = []) : bool;

    public function editMessage(string $board, int $message_id, array $edits) : bool;

    public function searchForTopics(string $criteria) : array|bool;

    public function searchForUsers(string $criteria, array $options = array()) : array|bool;

    public function searchForPosts(string $criteria, array $options) : array|bool;

    public function replyToTopic(int $board_id, string $topic, array $response) : bool;
}