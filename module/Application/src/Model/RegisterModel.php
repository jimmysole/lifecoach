<?php

namespace Application\Model;


use Laminas\Db\TableGateway\TableGateway;

use Application\Model\Filters\Register;
use Application\Classes\Register as RegisterClass;


class RegisterModel
{
    protected $table_gateway;
    
    
    public function __construct(TableGateway $gateway)
    {
        $gateway instanceof TableGateway ? $this->table_gateway = $gateway : $this->table_gateway = null;
    }
    
    
    public function handleRegistration(Register $fregister)
    {
        $register = new RegisterClass($this->table_gateway);
        
        $register->handleRegistration([
            'username'   => $fregister->username,
            'password'   => $fregister->password,
            'email'      => $fregister->email_address,
            'first_name' => $fregister->first_name,
            'last_name'  => $fregister->last_name,
            'address'    => $fregister->address,
            'city'       => $fregister->city,
            'state'      => $fregister->state,
            'zipcode'    => $fregister->zipcode,
            'country'    => $fregister->country
        ]);
    }
}