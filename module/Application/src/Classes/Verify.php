<?php
	namespace Application\Classes;
	
	
	use Laminas\Db\TableGateway\TableGateway;
	use Laminas\Db\Sql\Sql;
	use Laminas\Db\Sql\Insert;
	use Laminas\Db\Adapter\Adapter;
	
	use Application\Interfaces\VerifyInterface;
	
	class Verify implements VerifyInterface
	{
		protected $table_gateway;
		
		protected $code;
		
		
		public function __construct(TableGateway $gateway)
		{
			$this->table_gateway = $gateway;
		}
		
		
		
		public function authenticateCode(string $code) : bool
		{
			$this->code = !empty($code) ? $code : null;
			
			$select = $this->table_gateway->select(array('registration_code' => $this->code));
			
			$row = $select->current();
			
			if (!$row) {
				throw new \Exception(sprintf("Invalid registration code %s", $this->code));
			} else {
				$data = array(
					'username'   => $row['username'],
					'password'   => $row['password'],
					'email'      => $row['email'],
					'first_name' => $row['first_name'],
					'last_name'  => $row['last_name'],
					'address'    => $row['address'],
					'city'       => $row['city'],
					'state'      => $row['state'],
					'zipcode'    => $row['zipcode'],
					'country'    => $row['country'],
				);
				
				$sql = new Sql($this->table_gateway->getAdapter());
				
				$adapter = $this->table_gateway->getAdapter();
				
				$insert = new Insert('users');
				
				$insert->columns(array(
					'username', 'password', 'email', 'first_name', 'last_name', 'address', 'city', 'state', 'zipcode', 'country', 'active'
				))->values(array(
					'username'    => $data['username'],
					'password'     => $data['password'],
					'email'           => $data['email'],
					'first_name'   => $data['first_name'],
					'last_name'   => $data['last_name'],
					'address'       => $data['address'],
					'city'              => $data['city'],
					'state'            => $data['state'],
					'zipcode'        => $data['zipcode'],
					'country'        => $data['country'],
					'active'          => 1,
				));
				
				$execute = $adapter->query(
					$sql->buildSqlString($insert),
					Adapter::QUERY_MODE_EXECUTE
				);
				
				if ($execute->count() > 0) {
					$delete = $this->table_gateway->delete(array('id' => $row['id']));
					
					if ($delete > 0) {
						return true;
					} else {
						return false;
					}
				} else {
					return false;
				}
			}
		}
	}