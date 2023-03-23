<?php

namespace Application\Model;


use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Sql\Sql;

use Application\Classes\Logout as LogoutClass;

class LogoutModel
{
    protected TableGateway $table_gateway;
    protected Sql $sql;
    protected \Laminas\Db\Adapter\AdapterInterface $adapter;
    
    public function __construct(TableGateway $gateway) 
    {
        $this->table_gateway = $gateway;
        
        $this->sql = new Sql($this->table_gateway->getAdapter());
        
        $this->adapter = $this->table_gateway->getAdapter();
    }
    
    
    public function handleLogout(): LogoutClass
    {
        return new LogoutClass($this->table_gateway);
    }
}