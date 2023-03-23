<?php

namespace Application\Interfaces;


interface UserInterface
{

    public function sendMessage(array $params) : bool;

    public function requestSession(array $params) : bool;

    public function viewArticles(string $criteria = "") : array|bool;

    public function changePassword(string $user, string $old_pass, string $new_pass) : bool;
}