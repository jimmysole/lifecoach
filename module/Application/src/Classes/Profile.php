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


        public function getProfile() : array|bool
        {
            $select = $this->select->columns(['username', 'real_name', 'location', 'avatar', 'bio'])
                ->from('profile')
                ->where(['username' => $this->user]);

            $query = $this->gateway->getAdapter()->query(
                $this->sql->buildSqlString($select),
                Adapter::QUERY_MODE_EXECUTE
            );

            if ($query->count() > 0) {
                $profile = [];

                foreach ($query as $key => $value) {
                    $profile = array_merge($profile, array($key => $value));
                }

                return $profile;
            } else {
                return false;
            }
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
                    'avatar' => $avatar, 'bio' => $bio]);

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
            $image1 = [];

            if (count($image, 1) > 0) {
                $image1 = array_merge($image1, $image);
            }

            $path = getcwd() . '/public/profiles/' . $this->user . '/avatar/';

            if (!is_dir($path)) {
                mkdir(getcwd() . '/public/profiles/' . $this->user . '/avatar', 0777, true);
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


        public function editProfile(array $edits): bool
        {
            if (count($edits, 1) > 0) {
                $profile_edits = [];

                foreach ($edits as $key => $value) {
                    $profile_edits[$key] = $value;
                }

                $update = $this->update->table('profile')->set(['real_name' => $profile_edits['real_name'],
                    'location' => $profile_edits['location'], 'avatar' => $profile_edits['avatar'], 'bio' => $profile_edits['bio']])
                    ->where(['username' => $this->user]);

                $query = $this->gateway->getAdapter()->query(
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
        }


        public function deleteProfile(): bool
        {
            $files = array_diff(scandir(getcwd() . '/public/profiles/' . $this->user . '/avatar'), ['.', '..']);

            foreach ($files as $file) {
                unlink(getcwd() . '/public/profiles/' . $this->user . '/avatar/' . $file);
            }

            if (rmdir(getcwd() . '/public/profiles/' . $this->user . '/avatar')) {
                $delete = $this->delete->from('profile')
                    ->where(['username' => $this->user]);

                $query = $this->gateway->getAdapter()->query(
                    $this->sql->buildSqlString($delete),
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
        }
    }
