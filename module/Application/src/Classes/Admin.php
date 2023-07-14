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
            $title1 = !empty($title)             ? $title   : "Untitled";
            $subject1 = !empty($subject)           ? $subject : "No Subject";
            $body1 = !empty($body)              ? $body    : "Placeholder text for body";


            // insert the article now
            $insert = $this->insert->into('articles')->columns(['article_id', 'author', 'title', 'subject', 'body', 'date_written', 'image'])
                ->values(['article_id' => rand(0, 1000), 'author' => $this->user, 'title' => $title1, 'subject' => $subject1, 'body' => $body1,
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
            $image1 = count($image, 1) > 0 ? $image : [];

            // upload the image
            $path = getcwd() . '/public/images/articles/';

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
            $select = $this->select->columns(['user', 'counselor', 'message', 'submitted_date', 'approved_date', 'duration'])
                ->from('confirmed_sessions');

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
    }
