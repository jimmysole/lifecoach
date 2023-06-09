<?php

namespace Application\Model;



use Laminas\Db\TableGateway\TableGateway;

use Application\Classes\Index;


class IndexModel
{
    protected Index $index_class;
    
    
    public function __construct(TableGateway $gateway)
    {
        $this->index_class = new Index($gateway);
    }
    
	public function listArticles(): array
    {
		return $this->index_class->listArticles();
	}
}