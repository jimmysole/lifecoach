<?php

namespace Application\Model;


use Laminas\Db\TableGateway\TableGateway;



use Application\Classes\Login as LoginClass;


class LoginModel
{
    protected TableGateway $table_gateway;
    
  
    public function __construct(TableGateway $gateway)
    {
		$this->table_gateway = $gateway;
    }
    
    
    public function handleLogin(): LoginClass
    {
        return new LoginClass($this->table_gateway);
    }
}