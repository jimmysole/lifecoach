<?php

namespace Application\Interfaces;


interface LogoutInterface
{
    /**
     * Deletes the user session
     * @param string $username
     * @return bool
     * @throws \Exception
     */
    public function deleteSession(string $username) : bool;
}