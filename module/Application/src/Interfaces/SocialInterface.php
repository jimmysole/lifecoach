<?php


namespace Application\Interfaces;


interface SocialInterface
{
    public function sendChatRequest(string $with, array $params) : SocialInterface|bool;

    public function viewChatRequests() : array|bool;
}
