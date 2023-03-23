<?php

	namespace Application\Classes;
	
	use Laminas\Db\TableGateway;
	use Laminas\Db\Sql;
	use Laminas\Db\Sql\Insert;
	use Laminas\Db\Adapter;
	use Laminas\Db\Adapter\Exception;
	
	use Application\Interfaces;
	use Application\Model\Filters\Login as LoginFilter;

	
	class Login implements Interfaces\LoginInterface
	{
		protected TableGateway\TableGateway $table_gateway;
		protected Sql\Sql $sql;
		protected Adapter\AdapterInterface $adapter;
		
		
		public function __construct(TableGateway\TableGateway $gateway)
		{
			$this->table_gateway = $gateway;
			
			$this->sql = new Sql\Sql($this->table_gateway->getAdapter());
			
			$this->adapter = $this->table_gateway->getAdapter();
		}
		
		
		public function verifyCredentials(LoginFilter $credentials): array
		{
			try {
				$adapter = $this->sql->getAdapter()->getDriver()->getConnection();
				
				$query = $adapter->execute("SELECT password, is_admin FROM users WHERE username = '" . $credentials->username . "'");
				
				if ($query->count() > 0) {
					$data = [];
					
					foreach ($query as $row) {
						$data = $row;
					}
					
					if (password_verify($credentials->password, $data['password'])) {
                        if ($data['is_admin'] == 1) {
                            return [ 'admin' => true, 'pass' => $data['password']];
                        } else {

                            return [ 'member' => true, 'pass' => $data['password'] ];
                        }
					} else {
						throw new \Exception("Invalid user credentials given, please try again.");
					}
                }
			} catch (Exception\InvalidQueryException $e) {
				return [ 'message' => 'An error occurred while verifying your credentials, please try again.' ];
			} catch (\Exception $e) {
				return [ 'message' => $e->getMessage() ];
			}

            return [];
		}
		
		
		public function checkSession(array $info): bool
		{
			$adapter = $this->sql->getAdapter()->getDriver()->getConnection();
			
			$query = $adapter->execute("SELECT active AS active_user FROM sessions WHERE username = '" . $info['username'] . "'");
			
			foreach ($query as $row) {
				if ($row['active_user'] == 1) {
					return false;
				}
			}
			
			return true;
		}
		
		
		public function insertSession(array $info): bool
		{
			$insert = new Insert('sessions');
			
			$insert->columns(array(
				'username', 'password', 'active', 'session_id'
			))->values(array(
				'username' => $info['username'], 'password' => $info['password'], 'active' => 1, 'session_id' => $info['session_id']
			));
			
			$this->adapter->query(
				$this->sql->buildSqlString($insert),
				Adapter\Adapter::QUERY_MODE_EXECUTE
			);
			
			return true;
		}
	}