<?php

namespace Application\Model;


use Application\Classes\Profile;
use Application\Interfaces\ProfileInterface;
use Laminas\Db\TableGateway\TableGateway;

class ProfileModel
{
    public TableGateway $tableGateway;

    protected string $user;

    private Profile $profile;


    public function __construct(TableGateway $gateway, string $user)
    {
        $this->tableGateway = $gateway;
        $this->user = $user;

        $this->profile = new Profile($this->tableGateway, $this->user);
    }


    public function uploadAvatarImage(array $image): ProfileInterface|bool
    {
        return $this->profile->uploadProfileAvatar($image);
    }


    public function createProfile(array $details): ProfileInterface|bool
    {
        return $this->profile->createProfile($details);
    }


    public function getProfile() : array|bool
    {
        return $this->profile->getProfile();
    }

    public function editProfile(array $details) : bool
    {
        return $this->profile->editProfile($details);
    }


    public function deleteProfile() : bool
    {
        return $this->profile->deleteProfile();
    }
}