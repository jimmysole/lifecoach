<?php

namespace Application\Model\Storage;


use Laminas\Authentication\Storage\Session;


class LoginAuthStorage extends Session
{
	
	public function rememberUser($option = 0, $time = 1800): void
    {
        if ($option == 1) {
            $this->session->getManager()->rememberMe($time);
        }
    }
    
    
    public function forgetUser(): void
    {
        $this->session->getManager()->forgetMe();
    }
}