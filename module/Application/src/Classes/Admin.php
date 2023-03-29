<?php
	
	namespace Application\Classes;
	
	use Laminas\Db\Adapter\Adapter;
	use Laminas\Db\Sql\Delete;
	use Laminas\Db\Sql\Insert;
	use Laminas\Db\Sql\Select;
	use Laminas\Db\Sql\Sql;
	use Laminas\Db\Sql\Update;
	use Laminas\Db\TableGateway\TableGateway;
    use Laminas\Mail;
	
	use Application\Interfaces\AdminInterface;
	
	
	class Admin implements AdminInterface
	{
		private TableGateway $gateway;
		private Sql $sql;
		private Insert $insert;
		private Select $select;
		private Update $update;
		private Delete $delete;
		
		private string $user;

		private string $title;
		private string $subject;
		private string $body;
        private array $image;

        private array $meeting_details = [];
		
		public function __construct(TableGateway $gateway, string $user = "")
		{
			$this->gateway = $gateway;
			$this->user = $user;
			
			$this->sql = new Sql($this->gateway->getAdapter());
			$this->insert = new Insert();
			$this->select = new Select();
			$this->update = new Update();
			$this->delete = new Delete();
		}
		

		///////////////////////////////////////////////////////////////////////////////////////////////
        // meeting methods
        public function getRequestedMeetings(): array|bool
        {
            $select = $this->select->columns(['id', 'user', 'counselor', 'message', 'time'])
                ->from('requested_sessions')
                ->where(['counselor' => $this->user]);

            $query = $this->gateway->getAdapter()->query(
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


        public function getConfirmedMeetings() : array|bool
        {
            $select = $this->select->columns(['id', 'user', 'message', 'submitted_date', 'approved_date', 'duration'])
                ->from('confirmed_sessions')
                ->where("counselor = '" . $this->user . "' AND started != 1");

            $query = $this->gateway->getAdapter()->query(
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


        public function confirmMeeting(array $details): AdminInterface|bool
		{
            if (count($details, 1) > 0) {
                foreach ($details as $key => $value) {
                    $this->meeting_details[$key] = $value;
                }
            } else {
                return false;
            }

            // insert the info into the table
            $insert = $this->insert->into('confirmed_sessions')->columns(['user', 'counselor', 'message', 'submitted_date', 'approved_date', 'duration'])
                ->values(['user' => $this->meeting_details['client'], 'counselor' => $this->user, 'message' => $this->meeting_details['message'],
                   'submitted_date' => $this->meeting_details['submitted_date'], 'approved_date' => $this->meeting_details['meeting_time'], 'duration' => $this->meeting_details['duration']]);

            $query = $this->gateway->getAdapter()->query(
                $this->sql->buildSqlString($insert), Adapter::QUERY_MODE_EXECUTE
            );

            if ($query->count() > 0) {
                // delete from requested sessions table
                $delete = $this->delete->from('requested_sessions')
                    ->where(['id' => $this->meeting_details['id']]);

                $query = $this->gateway->getAdapter()->query(
                    $this->sql->buildSqlString($delete),
                    Adapter::QUERY_MODE_EXECUTE
                );

                if ($query->count() > 0) {
                    // send an email to the user saying his or her session has been confirmed
                    $select = $this->select->columns(['email'])
                        ->from('users')
                        ->where(['username' => $this->meeting_details['client']]);

                    $query = $this->gateway->getAdapter()->query(
                        $this->sql->buildSqlString($select),
                        Adapter::QUERY_MODE_EXECUTE
                    );

                    if ($query->count() > 0) {
                        $row = [];

                        foreach ($query as $key => $value) {
                            $row[$key] = $value;
                        }

                        $mail = new Mail\Message();
                        $mail->setSubject("Your upcoming meeting with Kevin Benitez.")
                            ->setFrom('kevinbenitezcoaching@outlook.com')
                            ->addTo($row['email'])
                            ->setBody("Your session was confirmed for " . $this->meeting_details['meeting_time'] . " . Please arrive at least five minutes early. Any questions, please email Kevin.");

                        $transport = new Mail\Transport\Sendmail();
                        $transport->send($mail);

                        return $this;
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


        public function cancelMeeting(array $meeting, array $params = []): AdminInterface|bool
        {
            if (count(array_values($meeting), 1) > 1) {
                $ids = [];

                foreach ($meeting as $k => $v) {
                    $ids[$k] = $v;
                }

                if (!preg_match("/[0-9+]/", implode(", ", array_values($ids)))) {
                    return false;
                } else {
                    // meeting id valid
                    // fetch from db
                    $select = $this->select->columns(['id', 'user', 'counselor', 'message', 'submitted_date', 'approved_date', 'duration'])
                        ->from('confirmed_sessions')
                        ->where(['id' => 'IN(' . implode(", ", array_values($ids)) . ')']);

                    $query = $this->gateway->getAdapter()->query(
                        $this->sql->buildSqlString($select),
                        Adapter::QUERY_MODE_EXECUTE
                    );

                    if ($query->count() > 0) {
                        // now delete
                        $delete = $this->delete->from('confirmed_sessions')->where(['id' => 'IN (' . implode(", ", array_values($ids)) . ')']);

                        $query = $this->gateway->getAdapter()->query(
                            $this->sql->buildSqlString($delete),
                            Adapter::QUERY_MODE_EXECUTE
                        );

                        if ($query->count() > 0) {
                            return $this;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                }
            } else {
                if (!preg_match("/[0-9+]/", intval($meeting['id']))) {
                    return false;
                } else {
                    // meeting id valid
                    // fetch from db
                    $select = $this->select->columns(['id', 'user', 'counselor', 'message', 'submitted_date', 'approved_date', 'duration'])
                        ->from('confirmed_sessions')
                        ->where(['id' => $meeting['id']]);

                    $query = $this->gateway->getAdapter()->query(
                        $this->sql->buildSqlString($select),
                        Adapter::QUERY_MODE_EXECUTE
                    );

                    if ($query->count() > 0) {
                        // now delete
                        $delete = $this->delete->from('confirmed_sessions')->where(['id' => $meeting['id']]);

                        $query = $this->gateway->getAdapter()->query(
                            $this->sql->buildSqlString($delete),
                            Adapter::QUERY_MODE_EXECUTE
                        );

                        if ($query->count() > 0) {
                            return $this;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                }
            }
        }


        public function rescheduleMeeting(array $meeting_id, array $params = []): AdminInterface|bool
        {
           if (!preg_match("/[0-9+]/", intval($meeting_id['id']))) {
               return false;
           } else {
               // meeting id valid
               // fetch from db
               $select = $this->select->columns(['id', 'approved_date', 'duration'])
                   ->from('confirmed_sessions')
                   ->where(['id' => intval($meeting_id['id'])]);

               $query = $this->gateway->getAdapter()->query(
                   $this->sql->buildSqlString($select),
                   Adapter::QUERY_MODE_EXECUTE
               );

               if ($query->count() > 0) {
                   $update = $this->update->table('confirmed_sessions')
                       ->set(['approved_date' => $meeting_id['approved_date'], 'duration' => $meeting_id['duration']])
                       ->where(['id' => intval($meeting_id['id'])]);

                   $query = $this->gateway->getAdapter()->query(
                       $this->sql->buildSqlString($update),
                       Adapter::QUERY_MODE_EXECUTE
                   );

                   if ($query->count() > 0) {
                       return $this;
                   } else {
                       return false;
                   }
               } else {
                   return false;
               }
           }
        }


        public function startMeeting(array $meeting_id): bool
        {
            // check if there is a pending meeting with the id
            if (preg_match("/[0-9+]/", $meeting_id['id'])) {
                $select = $this->select->columns(['user', 'approved_date', 'duration'])
                    ->from('confirmed_sessions')
                    ->where(['id' => $meeting_id['id']]);

                $query = $this->gateway->getAdapter()->query(
                    $this->sql->buildSqlString($select),
                    Adapter::QUERY_MODE_EXECUTE
                );

                if ($query->count() > 0) {
                    $update = $this->update->table('confirmed_sessions')->set(['started' => 1])
                        ->where(['id' => $meeting_id['id']]);

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
            } else {
                return false;
            }
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////



        ///////////////////////////////////////////////////////////////////////////////////////////////
        // article methods
        public function postArticle(string $subject, string $title, string $body, string $image): AdminInterface|bool
        {
            $this->title     = !empty($title)             ? $title   : "Untitled";
            $this->subject   = !empty($subject)           ? $subject : "No Subject";
            $this->body      = !empty($body)              ? $body    : "Placeholder text for body";


            // insert the article now
            $insert = $this->insert->into('articles')->columns(['article_id', 'author', 'title', 'subject', 'body', 'date_written', 'image'])
                ->values(['article_id' => rand(0, 1000), 'author' => $this->user, 'title' => $this->title, 'subject' => $this->subject, 'body' => $this->body,
                    'date_written' => date('Y-m-d h:i:s'), 'image' => $image]);

            $query = $this->gateway->getAdapter()->query(
                $this->sql->buildSqlString($insert), Adapter::QUERY_MODE_EXECUTE
            );

            if ($query->count() > 0) {
                return $this;
            } else {
                return false;
            }
        }


        public function uploadArticleImage(array $image) : AdminInterface|bool
        {
            $this->image = count($image, 1) > 0 ? $image : [];

            // upload the image
            $path = getcwd() . '/public/images/articles/';

            if (is_uploaded_file($this->image['file']['tmp_name'])) {
                if (move_uploaded_file($this->image['file']['tmp_name'], $path . $this->image['file']['name'])) {
                    return $this;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }


        public function viewArticles(): array
        {
            $select = $this->select->columns(['article_id', 'author', 'title', 'subject', 'body', 'date_written'])->from('articles')->where('id IS NOT NULL');

            $query = $this->sql->getAdapter()->query(
                $this->sql->buildSqlString($select),
                Adapter::QUERY_MODE_EXECUTE
            );

            if ($query->count() > 0) {
                $rows = [];

                foreach ($query as $values) {
                    $rows[] = $values;
                }
            } else {
                return [];
            }

            return $rows;
        }


        public function removeArticle(int $article_id): AdminInterface|bool
        {
            if (preg_match("/[0-9+]/", $article_id)) {
                // article id is an int
                // locate it now
                $delete = $this->delete->from('articles')->where(['article_id' => $article_id]);

                $query = $this->gateway->getAdapter()->query(
                    $this->sql->buildSqlString($delete),
                    Adapter::QUERY_MODE_EXECUTE
                );

                if ($query->count() > 0) {
                    // article removed
                    return $this;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }


        public function editArticle(int $article_id, array $edits = []): AdminInterface|bool
        {
            if (preg_match("/[0-9+]/", $article_id)) {
                if (count($edits, 1) > 0) {
                    $article_edits = [];

                    foreach ($edits as $key => $value) {
                        $article_edits[$key] = $value;
                    }

                    // find the article
                    $select = $this->select->columns(['id'])
                        ->from('articles')
                        ->where(['article_id' => $article_id]);

                    $query = $this->gateway->getAdapter()->query(
                        $this->sql->buildSqlString($select),
                        Adapter::QUERY_MODE_EXECUTE
                    );

                    if ($query->count() > 0) {
                        // article found
                        $title = $article_edits['title'];
                        $subject = $article_edits['subject'];
                        $body = $article_edits['body'];

                        $update = $this->update->table('articles')
                            ->set(['title' => $title, 'subject' => $subject, 'body' => $body])
                            ->where(['article_id' => $article_id]);

                        $query = $this->gateway->getAdapter()->query(
                            $this->sql->buildSqlString($update),
                            Adapter::QUERY_MODE_EXECUTE
                        );

                        if ($query->count() > 0) {
                            return $this;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    // no edits were provided
                    // bail
                    return false;
                }
            } else {
                return false;
            }
        }
        //////////////////////////////////////////////////////////////////////////////////////////////////////



        //////////////////////////////////////////////////////////////////////////////////////////////////////
        // utility methods
        public function viewUserList(): array|bool
        {
            $select = $this->select->columns(['username', 'email', 'first_name', 'last_name', 'address', 'city', 'state', 'zipcode', 'country', 'active'])
                ->from('users');

            $query = $this->gateway->getAdapter()->query(
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


        public function upcomingSchedule(): array|bool
        {
            $select = $this->select->columns(['client', 'meeting_time', 'duration'])
                ->from('meetings');

            $query = $this->gateway->getAdapter()->query(
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


        public function setAccess(int $role_id, string $admin): AdminInterface|bool
        {
            if (preg_match("/[0-9+]", $role_id) && $admin != "") {
                if ($role_id == 1) {
                    // super admin
                    // all privileges
                    $privileges = [
                        'confirm_meeting'     => true,
                        'cancel_meeting'      => true,
                        'reschedule_meeting'  => true,
                        'start_meeting'       => true,
                        'post_article'        => true,
                        'remove_article'      => true,
                        'edit_article'        => true,
                        'view_users'          => true,
                        'upcoming_schedule'   => true,
                        'ban_user'            => true,
                        'unban_user'          => true,
                        'upload_file'         => true,
                        'remove_file'         => true,
                        'change_file_status'  => true,
                    ];

                    if (!file_exists('../../../data/' . $admin . 'txt')) {
                        // create the file
                        $fp = fopen("../../../data/$admin.txt", "w");

                        fwrite($fp, implode(",", $privileges));

                        fclose($fp);

                        // save file path to db
                        $update = $this->update->table('admins')
                            ->set(['role' => 1, 'perms_file' => '../../../data/' . $admin . 'txt'])
                            ->where(['username' => $admin]);

                        $query = $this->gateway->getAdapter()->query(
                            $this->sql->buildSqlString($update),
                            Adapter::QUERY_MODE_EXECUTE
                        );

                        if ($query->count() > 0) {
                            // info stored
                            return $this;
                        } else {
                            return false;
                        }
                    } else {
                        // overwrite the file contents
                        // file exists already, no need to insert it into database table
                        $put = file_put_contents('../../../data/' . $admin . '.txt', implode(",", $privileges));

                        if (false !== $put) {
                            return $this;
                        } else {
                            return false;
                        }
                    }
                } else if ($role_id == 2) {
                    // regular admin
                    // most privileges
                    $privileges = [
                        'confirm_meeting'     => true,
                        'cancel_meeting'      => true,
                        'reschedule_meeting'  => true,
                        'start_meeting'       => true,
                        'post_article'        => true,
                        'remove_article'      => true,
                        'edit_article'        => true,
                        'view_users'          => true,
                        'upcoming_schedule'   => true,
                        'ban_user'            => true,
                        'unban_user'          => true,
                    ];

                    if (!file_exists('../../../data/' . $admin . 'txt')) {
                        // create the file
                        $fp = fopen("../../../data/$admin.txt", "w");

                        fwrite($fp, implode(",", $privileges));

                        fclose($fp);

                        // save file path to db
                        $update = $this->update->table('admins')
                            ->set(['role' => 2, 'perms_file' => '../../../data/' . $admin . 'txt'])
                            ->where(['username' => $admin]);

                        $query = $this->gateway->getAdapter()->query(
                            $this->sql->buildSqlString($update),
                            Adapter::QUERY_MODE_EXECUTE
                        );

                        if ($query->count() > 0) {
                            // info stored
                            return $this;
                        } else {
                            return false;
                        }
                    } else {
                        // overwrite the file contents
                        // file exists already, no need to insert it into database table
                        $put = file_put_contents('../../../data/' . $admin . '.txt', implode(",", $privileges));

                        if (false !== $put) {
                            return $this;
                        } else {
                            return false;
                        }
                    }
                } else if ($role_id == 3) {
                    // moderator only privileges
                    $privileges = [
                        'confirm_meeting'     => true,
                        'cancel_meeting'      => true,
                        'reschedule_meeting'  => true,
                        'upcoming_schedule'   => true,
                        'ban_user'            => true,
                        'unban_user'          => true,
                    ];

                    if (!file_exists('../../../data/' . $admin . 'txt')) {
                        // create the file
                        $fp = fopen("../../../data/$admin.txt", "w");

                        fwrite($fp, implode(",", $privileges));

                        fclose($fp);

                        // save file path to db
                        $update = $this->update->table('admins')
                            ->set(['role' => 3, 'perms_file' => '../../../data/' . $admin . 'txt'])
                            ->where(['username' => $admin]);

                        $query = $this->gateway->getAdapter()->query(
                            $this->sql->buildSqlString($update),
                            Adapter::QUERY_MODE_EXECUTE
                        );

                        if ($query->count() > 0) {
                            // info stored
                            return $this;
                        } else {
                            return false;
                        }
                    } else {
                        // overwrite the file contents
                        // file exists already, no need to insert it into database table
                        $put = file_put_contents('../../../data/' . $admin . '.txt', implode(",", $privileges));

                        if (false !== $put) {
                            return $this;
                        } else {
                            return false;
                        }
                    }
                } else {
                    // invalid rank
                    return false;
                }
            } else {
                return false;
            }
        }


        public function banUser(int $user_id, array $params = []): AdminInterface|bool
        {
            if (preg_match("/[0-9+]/", $user_id)) {
                // get the user from the user table
                $select = $this->select->columns(['id'])->from('users')
                    ->where(['id' => $user_id]);

                $query = $this->gateway->getAdapter()->query(
                    $this->sql->buildSqlString($select),
                    Adapter::QUERY_MODE_EXECUTE
                );

                $row = [];

                if ($query->count() > 0) {
                    foreach ($query as $k => $v) {
                        $row[$k] = $v;
                    }
                } else {
                    return false;
                }


                if (count($params, 1) > 0) {
                    $holder = [];

                    foreach ($params as $key => $value) {
                        $holder[$key] = $value;
                    }

                    $insert = $this->insert->into('banned')
                       ->columns(['user_id', 'reason'])
                       ->values(['user_id' => $row['id'], 'reason' => $holder['reason']]);

                    $q = $this->gateway->getAdapter()->query(
                        $this->sql->buildSqlString($insert),
                        Adapter::QUERY_MODE_EXECUTE
                    );

                    if ($q->count() > 0) {
                        // user put into banned table
                        return $this;
                    } else {
                        return false;
                    }
                } else {
                    // no reason was given
                    // just ban user without reason
                    $insert = $this->insert->into('banned')
                        ->columns(['user_id'])
                        ->values(['user_id' => $row['id']]);

                    $q = $this->gateway->getAdapter()->query(
                        $this->sql->buildSqlString($insert),
                        Adapter::QUERY_MODE_EXECUTE
                    );

                    if ($q->count() > 0) {
                        return $this;
                    } else {
                        return false;
                    }
                }
            } else {
                return false;
            }
        }


        public function unbanUser(int $user_id): AdminInterface|bool
        {
            if (preg_match("/[0-9+]/", $user_id)) {
                // find the user in the banned table
                $select = $this->select->columns(['id'])
                    ->from('banned')
                    ->where(['user_id' => $user_id]);

                $query = $this->gateway->getAdapter()->query(
                    $this->sql->buildSqlString($select),
                    Adapter::QUERY_MODE_EXECUTE
                );

                if ($query->count() > 0) {
                    $row = [];

                    foreach ($query as $key => $value) {
                        $row[$key] = $value;
                    }

                    $delete = $this->delete->from('banned')
                        ->where(['id' => $row['id']]);

                    $q = $this->gateway->getAdapter()->query(
                        $this->sql->buildSqlString($delete),
                        Adapter::QUERY_MODE_EXECUTE
                    );

                    if ($q->count() > 0) {
                        return $this;
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


        public function uploadFile(array $file, string $access = 'private'): AdminInterface|bool
        {
            if (is_file($file['name'])) {
                $path = "../../../../data/" . $this->user;

                if (is_dir($path)) {
                    // proceed to upload the file
                    if (is_uploaded_file($file['tmp'])) {
                        if (move_uploaded_file($file['tmp'], $path . '/' . $file['name'])) {
                            return $this;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    // create the dir
                    mkdir("../../../../data/" . $this->user);

                    $new_path = "../../../../data/" . $this->user;

                    // proceed to upload the file
                    if (is_uploaded_file($file['tmp'])) {
                        if (move_uploaded_file($file['tmp'], $new_path . '/' . $file['name'])) {
                            return $this;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                }
            } else {
                return false;
            }
        }


        public function removeFile(string $file): AdminInterface|bool
        {
            if (is_file($file)) {
                if (unlink($file)) {
                    return $this;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }


        public function changeFileStatus(string $file, string $access): AdminInterface|bool
        {
            if (is_file($file)) {
                if ($access == 'private') {
                    // restrict access
                    // based on session id?
                    // or chmod properties?
                    if (is_dir("../../../../data/" . $this->user)) {
                        chmod($file, 0600); // read and write for owner ($this->user)

                        return $this;
                    } else {
                        return false;
                    }
                } else if ($access == 'public') {
                    if (is_dir("../../../../data/" . $this->user)) {
                        chmod($file, 0777); // all access for everyone

                        return $this;
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
