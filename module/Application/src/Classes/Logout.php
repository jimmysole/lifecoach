<?php

	namespace Application\Classes;
	
	use Application\Interfaces\LogoutInterface;

    use Laminas\Db\Adapter\AdapterInterface;
    use Laminas\Db\TableGateway\TableGateway;
	use Laminas\Db\Sql\Sql;
	use Laminas\Db\Sql\Delete;
	use Laminas\Db\Adapter\Adapter;
	
	
	class Logout implements LogoutInterface
	{
		protected TableGateway $table_gateway;

		protected Sql $sql;

		protected AdapterInterface $adapter;
		
		
		public function __construct(TableGateway $gateway)
		{
			$this->table_gateway = $gateway;
			
			$this->sql = new Sql($this->table_gateway->getAdapter());
			
			$this->adapter = $this->table_gateway->getAdapter();
		}
		
		
		public function deleteSession(string $username): bool
		{
			$delete = new Delete('sessions');
			
			$delete->where([
				'username'  => $username
			]);
			
			$this->adapter->query(
				$this->sql->buildSqlString($delete),
				Adapter::QUERY_MODE_EXECUTE
			);
			
			return true;
		}
	}