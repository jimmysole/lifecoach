<?php
	
	namespace Application\Classes;
	
	use Exception;
    use Laminas\Db\Adapter\Adapter;
    use Laminas\Db\Sql\Sql;
    use Laminas\Db\TableGateway\TableGateway;
	use Laminas\Db\Sql\Select;
	use Laminas\Db\Sql\Insert;
	
	use Laminas\Mail;
	
	use Application\Interfaces\ConferenceInterface;



	class Conference implements ConferenceInterface
	{
			private string $user;

			private TableGateway $gateway;

            private Sql $sql;

			private string $title = "Individual Chat Session with Kevin Benitez";

			private array $room_details = [];

			private Insert $insert;

			public function __construct(TableGateway $gateway, string $user)
			{
				$this->gateway = $gateway;
				$this->user = $user;
				$this->insert =  new Insert('conferences');
                $this->sql = new Sql();
			}

			public function startConference(): ConferenceInterface
			{
				// select to see if the user is scheduled
				$select = $this->gateway->select(function (Select  $select) {
					$select->where(['user' => $this->user, 'scheduled' => 1]);
				});


				if ($select->count() > 0) {
					// user is scheduled
					// see how long for
					$rowset = [];

					foreach ($select as $rows) {
						$rowset[] = $rows;
					}

					$get_time = $rowset['scheduled_time'];

					switch ($get_time) {
						case 1800:
							// 30 mins
							// send a reminder
							// and then get the session ready
							$user_email = $rowset['email'];

							$mail = new Mail\Message();
							$mail->setBody("Hello " . $this->user . ",\n\n You have an upcoming session with Kevin Benitez scheduled for " . $rowset['appt_time'] . ". To join this meeting,
							click the following link: <a href=\"https://kevinbenitez.com/appointment/join/$this->user\">Join Now</a>\n\nWe look forward to seeing you soon!")
							->setFrom('appointments@kevinbenitez.com', 'Kevin Benitez')
							->addTo($user_email, $this->user)
							->setSubject("Meeting with Kevin Benitez");

							$send = new Mail\Transport\Sendmail();
							$send->send($mail);

						    // prepare the chat room
							$layout = @file_get_contents(getcwd() . '/Application/view/layout/chat_default.phtml');

							$get_title = preg_match("/<title>(.*)<\/title>/siU", $layout, $matches) === false ? null : $matches[1];
							$replace = preg_replace('/\s+/', $this->title, $get_title);

							$replace = rtrim($replace);

							$insert = $this->gateway->insertWith($this->insert->columns(
								['user', 'appt_time', 'title']
							)->values(
								['user' => $this->user, 'appt_time' => $rowset['appt_time'], 'title' => $replace]
							));

							if ($insert > 0) {
								$notify = [
										'config' => [
											'chat_name  ' => $this->user . " chatting with Kevin Benitez ($replace). Session started at " . $rowset['appt_time'],
											'chat_admin'  => 'kb_admin',
											'chat_type'    => [
												'video' => [
													'enable_audio'        => true,
													'enabled_html5vp'     => true,
													'use_zoom'            => true,
												],

												'audio' => [
													'enabled_video'      => false,
													'enabled_html5vp'    => false,
													'use_zoom'           => true,
												],
											],
										],
								];


							} else {
								throw new Exception("Error setting up the room, please try again.");
							}
						break;

						case 3600:
							// 1 hour
							// send a reminder
							// and then get the session ready
							$user_email = $rowset['email'];

							$mail = new Mail\Message();
							$mail->setBody("Hello " . $this->user . ",\n\n You have an upcoming session with Kevin Benitez scheduled for " . $rowset['appt_time'] . ". To join this meeting,
							click the following link: <a href=\"https://kevinbenitez.com/appointment/join/$this->user\">Join Now</a>\n\nWe look forward to seeing you soon!")
								->setFrom('appointments@kevinbenitez.com', 'Kevin Benitez')
								->addTo($user_email, $this->user)
								->setSubject("Meeting with Kevin Benitez");

							$send = new Mail\Transport\Sendmail();
							$send->send($mail);

							break;
						default:
							// no time limit
							// bail
							throw new Exception("No session time was chosen.");
					}
				} else {
					throw new Exception("You are currently not scheduled for a meeting.");
				}

				return $this;
			}


			public function endConference(): ConferenceInterface
			{

				return $this;
			}


			public function prepareRoom(array $details): ConferenceInterface
			{
				if (count($details) > 0) {
					foreach ($details as $key => $value) {
						$this->room_details[$key] = $value;
					}

					if ($this->room_details['style'] == 'default') {
						// load the default layout
						$layout = getcwd() . "/Application/view/layout/chat_default.phtml";

						$get_file = @file_get_contents($layout);

						if (false !== $get_file) {
							// set the title
							if (preg_match("/<title>(.*)<\/title>/siU", $get_file, $matches)) {
								$title = preg_replace('/\s+/', $this->room_details['title'], $matches[1]);
								$title = trim($title);

								// save to file or db?
								$insert = $this->gateway->insertWith($this->insert->columns([
									'title', 'user', 'admin', 'active'
								])->values([
									'title'    => $title,
									'user'   => $this->user,
									'admin' => 'Kevin Benitez',
									'active' => 1
								]));

								// further configure the room
                                // hmmm what to do
                                if ($insert > 0) {
                                    if (filter_var($this->room_details['zoom_url'], FILTER_VALIDATE_URL) !== false) {
                                        // valid url
                                        $insert = new Insert('zoom_links');

                                        // date format should be Y-m-d H:i:s
                                        $stmt = $insert->columns(['link', 'user', 'meeting_time'])
                                            ->values(['link' => $this->room_details['zoom_url'], 'user' => $this->user, 'meeting_time' => $this->room_details['meeting_time']]);

                                        $query = $this->gateway->getAdapter()->query(
                                            $this->sql->buildSqlString($stmt),
                                            Adapter::QUERY_MODE_EXECUTE
                                        );

                                        if ($query->count() > 0) {
                                            return $this;
                                        } else {
                                            throw new Exception("Error configuring the chat room settings, please try again.");
                                        }
                                    } else {
                                        throw new Exception("Supplied zoom link is not valid.");
                                    }
                                } else {
                                    throw new Exception("Error configuring the default settings, please try again.");
                                }
							}
						} else {
							throw new Exception("Error loading the room, please try again.");
						}
					} else if ($this->room_details['style'] == 'interactive') {
						// load the interactive layout
						$layout = getcwd() . "/Application/view/layout/interactive_room.phtml";
					} else {
						// just load the default layout
						$layout = getcwd() . "/Application/view/layout/chat_default.phtml";
					}
				} else {
					throw new Exception("No room details were set, please correct this and try again.");
				}

				return $this;
			}
	}
