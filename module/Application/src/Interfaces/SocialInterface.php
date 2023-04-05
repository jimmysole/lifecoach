<?php


namespace Application\Interfaces;


interface SocialInterface
{
    public function sendChatRequest(string $with, array $params) : SocialInterface|bool;

    public function viewOutgoingChatRequests() : array|bool;

    public function acceptChatRequest(array $params) : bool;
}
