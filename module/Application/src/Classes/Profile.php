<?php
	
	namespace Application\Classes;
	
	
	use Application\Interfaces\ProfileInterface;

    use Laminas\Db\Adapter\Adapter;
    use Laminas\Db\Sql\Delete;
    use Laminas\Db\Sql\Insert;
    use Laminas\Db\Sql\Select;
    use Laminas\Db\Sql\Sql;
    use Laminas\Db\Sql\Update;
    use Laminas\Db\TableGateway\TableGateway;


    class Profile implements ProfileInterface
	{
        public string $user;


        protected TableGateway $gateway;

        protected Sql $sql;

        protected Insert $insert;

        protected Select $select;

        protected Update $update;

        protected Delete $delete;


		private string $username;
        private string $reaL_name;
        private string $location;
        private array $avatar;
        private string $bio;

		private array $profile_details = [];


        public function __construct(TableGateway $gateway, string $user)
        {
            $this->gateway = $gateway;

            $this->user    = $user;

            $this->sql = new Sql($this->gateway->getAdapter());

            $this->insert = new Insert();

            $this->select = new Select();

            $this->update = new Update();

            $this->delete = new Delete();
        }

        public function createProfile(array $profile_details): bool
		{
			if (count($profile_details, 1) <= 0) {
				throw new \Exception("User profile details must be filled out.");
			}
			
 			foreach ($profile_details as $key => $value) {
 				$this->profile_details[$key] = $value;
		    }
 			
 			$this->username  = !empty($this->profile_details['username'])  ? $this->profile_details['username']  : $this->user;
            $this->reaL_name = !empty($this->profile_details['real_name']) ? $this->profile_details['real_name'] : "";
            $this->location  = !empty($this->profile_details['location'])  ? $this->profile_details['location']  : "";
            $this->avatar    = count($this->profile_details['avatar'], 1) > 0 ? array_merge_recursive($this->avatar, $this->profile_details['avatar']) : [];
            $this->bio       = !empty($this->profile_details['bio']) ? $this->profile_details['bio'] : "";

            if (is_file($this->avatar['file'])) {
                if (!is_dir(getcwd() . '/profiles/' . $this->user . '/avatar')) {
                    mkdir(getcwd() . '/profiles/' . $this->user . '/avatar', 0777);

                }

                if (move_uploaded_file($this->avatar['tmp_file'], $this->avatar['file'])) {
                    $insert = new Insert('profile');

                    $insert->columns(['username', 'real_name', 'location', 'avatar', 'bio'])
                        ->values(['username' => $this->username, 'real_name' => $this->reaL_name, 'location' => $this->location,
                            'avatar' => $this->avatar['path'], 'bio' => $this->bio]);

                    $query = $this->gateway->getAdapter()->query(
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
            } else {
                $insert = new Insert('profile');

                $insert->columns(['username', 'real_name', 'location', 'bio'])
                    ->values(['username' => $this->username, 'real_name' => $this->reaL_name, 'location' => $this->location, 'bio' => $this->bio]);

                $query = $this->gateway->getAdapter()->query(
                    $this->sql->buildSqlString($insert),
                    Adapter::QUERY_MODE_EXECUTE
                );

                if ($query->count() > 0) {
                    return true;
                } else {
                    return false;
                }
            }
		}
	}
