<?php


namespace Application\Interfaces;


interface SocialInterface
{
    public function viewOnlineUsers() : array|bool;

    public function sendChatRequest(string $with, array $params) : SocialInterface|bool;

    public function viewOutgoingChatRequests() : array|bool;

    public function viewIncomingChatRequests() : array|bool;

    public function acceptChatRequest(array $params) : bool;

    public function denyChatRequest(array $params) : bool;
}
