<?php

namespace Application\Model;


use Laminas\Db\TableGateway\TableGateway;

use Application\Classes\Verify;


class VerifyModel
{
    protected $table_gateway;
    
    
    public function __construct(TableGateway $gateway) 
    {
        $this->table_gateway = $gateway;
    }
    
    
    public function authenticate($code)
    {
        $verify = new Verify($this->table_gateway);
        
        try {
	        $verify->authenticateCode($code);
        } catch (\Exception $e) {
        	return $e->getMessage();
        }
    }
}