<?php
	
	namespace Application\Classes;
	
	use Application\Interfaces\UserInterface;
	
	use Laminas\Db\Adapter\Adapter;
	use Laminas\Db\Sql\Delete;
	use Laminas\Db\Sql\Insert;
	use Laminas\Db\Sql\Update;
    use Laminas\Db\Sql\Where;
    use Laminas\Db\TableGateway\TableGateway;
	use Laminas\Db\Sql\Sql;
	use Laminas\Db\Sql\Select;
	
	
	class User implements UserInterface
	{
		protected TableGateway $table_gateway;
		protected Sql $sql;
		protected Select $select;
		protected Insert $insert;
		protected Update $update;
		protected Delete $delete;

		
		
		public function __construct(TableGateway $gateway)
		{
			$this->table_gateway = $gateway;
			
			$this->sql = new Sql($this->table_gateway->getAdapter());
			
			$this->select = new Select();
			
			$this->insert = new Insert();
			
			$this->update = new Update();
			
			$this->delete = new Delete();
		}
		
		
        public function sendMessage(array $params): bool
        {
            if (count($params, 1) > 0) {
                $info = [];

                foreach ($params as $key => $value) {
                    $info[$key] = $value;
                }

                // insert the message into the database
                $insert = $this->insert->into('messages')
                    ->columns(['from', 'to', 'message', 'date_sent'])
                    ->values(['from' => $info['from'], 'to' => $info['to'], 'message' => $info['message'], 'NOW()']);

                $query = $this->table_gateway->getAdapter()->query(
                    $this->sql->buildSqlString($insert), Adapter::QUERY_MODE_EXECUTE
                );

                if ($query->count() > 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }


        public function requestSession(array $params): bool
        {
            if (count($params, 1) > 0) {
                $data = [];

                foreach ($params as $key => $value) {
                    $data[$key] = $value;
                }

                $insert = $this->insert->into('requested_sessions')
                    ->columns(['user', 'with', 'message', 'time'])
                    ->values(['user' => $data['user'], 'with' => $data['with'], 'message' => $data['message'], 'time' => $data['time']]);

                $query = $this->table_gateway->getAdapter()->query(
                    $this->sql->buildSqlString($insert),
                    Adapter::QUERY_MODE_EXECUTE
                );

                if ($query->count() > 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
            return true;
        }


        public function viewArticles(string $criteria = ""): array|bool
        {
            if (empty($criteria)) {
                // display all articles
                $select = $this->select->columns(['author', 'title', 'body', 'date_written'])->from('articles')
                    ->where('id IS NOT NULL');

            } else {
                $select = $this->select->columns(['author', 'title', 'body', 'date_written'])->from('articles')
                    ->where(function (Where $where) use ($criteria) {
                        $where->like('title', "%$criteria%");
                    });
            }

            $query = $this->table_gateway->getAdapter()->query(
                $this->sql->buildSqlString($select),
                Adapter::QUERY_MODE_EXECUTE
            );

            if ($query->count() > 0) {
                $rows = [];

                foreach ($query as $key => $value) {
                    $rows[$key] = $value;
                }

                return $rows;
            } else {
                return false;
            }
        }


        public function changePassword(string $user, string $old_pass, string $new_pass): bool
        {
            if (!empty($user) && !empty($old_pass) && !empty($new_pass)) {
                $select = $this->select->columns(['password'])
                    ->from('users')
                    ->where(['username' => $user]);

                $query = $this->table_gateway->getAdapter()->query(
                    $this->sql->buildSqlString($select),
                    Adapter::QUERY_MODE_EXECUTE
                );

                if ($query->count() > 0) {
                    $row = [];

                    foreach ($query as $key => $value) {
                        $row[$key] = $value;
                    }

                    if (password_verify($row['password'], PASSWORD_BCRYPT)) {
                        // set the new password
                        $npass = password_hash($new_pass, PASSWORD_BCRYPT);

                        $update = $this->update->table('users')
                            ->set(['password' => $npass])
                            ->where(['username' => $user]);

                        $query = $this->table_gateway->getAdapter()->query(
                            $this->sql->buildSqlString($update),
                            Adapter::QUERY_MODE_EXECUTE
                        );

                        if ($query->count() > 0) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }