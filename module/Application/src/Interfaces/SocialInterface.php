<?php


namespace Application\Interfaces;

use Application\Classes\Forums;

interface SocialInterface
{
    public function viewOnlineUsers() : array|bool;

    public function sendChatRequest(string $with, array $params) : SocialInterface|bool;

    public function viewOutgoingChatRequests() : array|bool;

    public function viewIncomingChatRequests() : array|bool;

    public function acceptChatRequest(array $params) : bool;

    public function denyChatRequest(array $params) : bool;

    public function currentChats() : array|bool;

    public function viewProfiles(array $criteria = []) : array|bool;

    public function forums(Forums $forums) : Forum;

    public function createProfile(array $profile_details) : bool;

    public function editProfile(array $edits) : bool;

    public function deleteProfile(string $reason) : bool;
}
