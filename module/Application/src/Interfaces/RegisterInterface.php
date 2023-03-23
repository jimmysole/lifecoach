<?php

namespace Application\Interfaces;



interface RegisterInterface
{
    /**
     * Handles the registration process
     * @param array $params
     * @return bool
     * @throws \Exception|\InvalidArgumentException
     */
    public function handleRegistration(array $params) : bool; 
}