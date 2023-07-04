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
 			
 			$username = $this->user;
            $reaL_name = !empty($this->profile_details['real_name']) ? $this->profile_details['real_name'] : "";
            $location = !empty($this->profile_details['location'])  ? $this->profile_details['location']  : "";
            $avatar = !empty($this->profile_details['avatar']) ? $this->profile_details['avatar'] : "";
            $bio = !empty($this->profile_details['bio']) ? $this->profile_details['bio'] : "";

            $insert = new Insert('profile');

            $insert->columns(['username', 'real_name', 'location', 'avatar', 'bio'])
                ->values(['username' => $username, 'real_name' => $reaL_name, 'location' => $location,
                    'avatar' => $avatar['path'], 'bio' => $bio]);

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


        public function uploadProfileAvatar(array $image): ProfileInterface|bool
        {
            $image1 = count($image, 1) > 0 ? $image : [];

            $path = getcwd() . '/public/profiles/' . $this->user . '/avatar/';

            if (!is_dir($path)) {
                mkdir(getcwd() . '/public/profiles/' . $this->user . '/avatar/');
            }

            if (is_uploaded_file($image1['file']['tmp_name'])) {
                if (move_uploaded_file($image1['file']['tmp_name'], $path . $image1['file']['name'])) {
                    return $this;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
