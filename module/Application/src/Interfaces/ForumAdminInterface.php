<?php


namespace Application\Interfaces;


interface ForumAdminInterface
{
    public function removePost(int $post_id, array $reason = array()) : bool;

    public function removeBoard(int $board_id, array $reason = array()) : bool;

    public function removeHotTopic(int $board_id, array $reason = array()) : bool;
}
