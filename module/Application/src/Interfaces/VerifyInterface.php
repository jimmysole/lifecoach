<?php

namespace Application\Interfaces;


interface VerifyInterface
{
    /**
     * Verifies the authentication code sent via email to user
     * @param string $code
     * @return bool
     * @throws \Exception
     */
    public function authenticateCode(string $code) : bool;
}