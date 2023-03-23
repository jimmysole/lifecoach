<?php

	namespace Application\Model;
	
	
	use Application\Classes\Admin;
    use Application\Classes\User;

    use Application\Interfaces\AdminInterface;
    use Laminas\Db\Adapter\Adapter;
	use Laminas\Db\Sql\Select;
	use Laminas\Db\Sql\Sql;
	use Laminas\Db\TableGateway\TableGateway;
	
	class UserModel
	{
		public TableGateway $table_gateway;
		protected string $user;
		protected \ArrayObject $get_user_id;
		protected Sql $sql;
        protected Select $select;
		
		private User $user_instance;

        private Admin $admin;
		
		public function __construct(TableGateway $gateway, string $user)
		{
			 $this->table_gateway = $gateway;
			 $this->sql = new Sql($this->table_gateway->getAdapter());
			 $this->user = $user;
			 $this->user_instance = new User($this->table_gateway);
             $this->admin = new Admin($this->table_gateway, $this->user);
             $this->select = new Select();
		}


        public function getUsername() : string|bool
        {

            $select = $this->select->columns(['username'])->from('users')
                ->where(['username' => $this->user ]);

            $query = $this->sql->getAdapter()->query(
                $this->sql->buildSqlString($select),
                Adapter::QUERY_MODE_EXECUTE
            );

            if ($query->count() > 0) {
                $row = [];

                foreach ($query as $value) {
                    $row = $value;
                }

                return $row['username'];
            } else {
                return false;
            }
        }


		public function getUserId()
		{

			
			$select = $this->select->columns(['id'])->from('users')
				->where(['username' => $this->user]);
			
			$query = $this->sql->getAdapter()->query(
				$this->sql->buildSqlString($select),
				Adapter::QUERY_MODE_EXECUTE
			);
			
			if ($query->count() > 0) {
				foreach ($query as $row) {
					$this->get_user_id = $row;
				}
				
				return $this->get_user_id;
			} else {
				return false;
			}
		}


        public function checkIfAdmin(string $user) : bool
        {

            $select = $this->select->columns(['is_admin'])->from('users')
                ->where(['username' => $user]);

            $query = $this->sql->getAdapter()->query(
                $this->sql->buildSqlString($select),
                Adapter::QUERY_MODE_EXECUTE
            );

            if ($query->count() > 0) {
                return true;
            } else {
                return false;
            }
        }



        // misc methods (not implemented in interface)
        public function getFullName() : array|bool
        {
            $select = $this->select->columns(['first_name', 'last_name'])->from('users')
                ->where(['username' => $this->user]);

            $query = $this->table_gateway->getAdapter()->query(
                $this->sql->buildSqlString($select),
                Adapter::QUERY_MODE_EXECUTE
            );

            if ($query->count() > 0) {
                $name = [];

                foreach ($query as $key => $value) {
                    $name = array_merge($name, [ $key => $value ]);
                }

                return $name;
            } else {
                return false;
            }
        }




        // admin methods for user
        public function getRequestedMeetings() : array|bool
        {
            return $this->admin->getRequestedMeetings();
        }


        public function getConfirmedMeetings() : array|bool
        {
            return $this->admin->getConfirmedMeetings();
        }


        public function confirmMeeting(array $details) : AdminInterface|bool
        {
            return $this->admin->confirmMeeting($details);
        }


        public function cancelMeeting(array $meeting, array $params = []) : AdminInterface|bool
        {
            return $this->admin->cancelMeeting($meeting, $params);
        }


        public function rescheduleMeeting(array $meeting_id, array $params = []) : AdminInterface|bool
        {
            return $this->admin->rescheduleMeeting($meeting_id, $params);
        }


        public function startMeeting(array $meeting_id) : bool
        {
            return $this->admin->startMeeting($meeting_id);
        }
	}