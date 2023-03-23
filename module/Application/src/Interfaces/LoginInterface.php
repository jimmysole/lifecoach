<?php

namespace Application\Interfaces;

use Application\Model\Filters\Login;


interface LoginInterface
{
    /**
     * Verifies the user credentials
     * @param Login $credentials
     * @return array
     * @throws \Exception
     */
    public function verifyCredentials(Login $credentials) : array;
    
    
    /**
     * Checks if a session is already active
     * 
     * @param array $info
     * @return bool
     */
    public function checkSession(array $info) : bool;
    
    
    /**
     * Inserts info into the session table
     * @param array $info
     * @return bool
     */
    public function insertSession(array $info) : bool;
}