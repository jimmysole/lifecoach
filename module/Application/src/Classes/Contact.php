<?php
	
	namespace Application\Classes;
	
	use Application\Interfaces\ContactInterface;
	use Laminas\Db\Adapter\Adapter;
	use Laminas\Db\Sql\Insert;
	use Laminas\Db\Sql\Sql;
	use Laminas\Db\TableGateway\TableGateway;
	
	
	class Contact implements ContactInterface
	{
		private array $contact_params = [];
		
		private Sql $sql;

		private Insert $insert;

		private TableGateway $gateway;
		
		
		public function __construct(TableGateway $gateway)
		{
			$this->gateway = $gateway;
			$this->sql = new Sql($this->gateway->getAdapter());
			$this->insert = new Insert();
		}
		
		
		public function sendMessage(array $params): bool
		{
			
			if (count($params, 1) > 0) {
				foreach ($params as $key => $value) {
					$this->contact_params[$key] = $value;
				}
			}
			
			// insert the message into the database table
			$insert = $this->insert->into('messages')
				->columns(['email', 'first_name', 'last_name', 'telephone', 'message'])
				->values([ 'email'          => $this->contact_params['email'],
					           'first_name' => $this->contact_params['first_name'],
					           'last_name'  => $this->contact_params['last_name'],
					           'telephone'   => $this->contact_params['telephone'],
					           'message'    => $this->contact_params['message'],
					        ]);
			
			$query = $this->sql->getAdapter()->query(
				$this->sql->buildSqlString($insert),
				Adapter::QUERY_MODE_EXECUTE
			);
			
			if ($query->count() > 0) {
				return true;
			}
			
		    return false;
		}
	}
