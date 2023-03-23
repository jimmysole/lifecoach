<?php

	namespace Application\Classes;
	
	use Application\Interfaces\RegisterInterface;
	
	use Laminas\Db\TableGateway\TableGateway;
	use Laminas\Db\Sql\Sql;
	use Laminas\Db\Sql\Select;
	use Laminas\Db\Sql\Insert;
	use Laminas\Db\Adapter\Adapter;
	
	use Laminas\View;
	use Laminas\View\Model\ViewModel;
	
	use Laminas\Mime\Message;
	use Laminas\Mime\Part;
	
	use Laminas\Mail\Message as MailMessage;
	use Laminas\Mail\Transport\Sendmail;
	
	
	class Register implements RegisterInterface
	{
		protected $table_gateway;
		protected $sql;
		protected $select;
		protected $insert;
		
		private $verification_code;
		
		protected $register_params = [];
		
		
		public function __construct(TableGateway $gateway)
		{
			$this->table_gateway = $gateway;
			
			$this->sql = new Sql($this->table_gateway->getAdapter());
			
			$this->select = new Select();
			
			$this->insert = new Insert();
			
			$this->verification_code = md5(uniqid('', true));
		}
		
		
		public function handleRegistration(array $params): bool
		{
			if (count($params)  > 0) {
				foreach ($params as $key => $value) {
					$this->register_params[$key] = $value;
				}
				
				$select = $this->select
					->columns([ 'id' ])
					->from('pending_users')
					->where([ 'username' => $this->register_params['username'] ]);
				
				$query = $this->sql->getAdapter()->query(
					$this->sql->buildSqlString($select),
					Adapter::QUERY_MODE_EXECUTE
				);
				
				if ($query->count() > 0) {
					throw new \Exception("You already have a pending request to join.");
				} else {
					$insert = $this->insert->into('pending_users')
						->columns([ 'username', 'password', 'email', 'first_name', 'last_name', 'address',
							'city', 'state', 'zipcode', 'country', 'registration_code' ])
						->values([ 'username' => $this->register_params['username'], 'password' => password_hash($this->register_params['password'], PASSWORD_BCRYPT),
							'email' => $this->register_params['email'], 'first_name' => $this->register_params['first_name'], 'last_name' => $this->register_params['last_name'],
							'address' => $this->register_params['address'], 'city' => $this->register_params['city'], 'state' => $this->register_params['state'],
							'zipcode' => $this->register_params['zipcode'], 'country' => $this->register_params['country'], 'registration_code' => $this->verification_code ]);
					
					$query = $this->sql->getAdapter()->query(
						$this->sql->buildSqlString($insert),
						Adapter::QUERY_MODE_EXECUTE
					);
					
					if ($query->count() > 0) {
						$view = new View\Renderer\PhpRenderer();
						$resolver = new View\Resolver\TemplateMapResolver();
						
						$resolver->setMap([
							'mailTemplate' => __DIR__ . '/../../view/email/email.phtml',
						]);
						
						$view->setResolver($resolver);
						
						$view_model = new ViewModel();
						
						$view_model->setTemplate('mailTemplate')->setVariables([
							'name' => $this->register_params['first_name'] . ' ' . $this->register_params['last_name'],
							'link' => $_SERVER['SERVER_NAME'] . '/verify/' . $this->verification_code
						]);
						
						$body_part = new Message();
						
						$body_msg = new Part($view->render($view_model));
						
						$body_msg->type = 'text/html';
						
						$body_part->setParts([$body_msg]);
						
						
						// actual building and sending of message
						$message = new MailMessage();
						
						$message->addFrom('register@christiannetwork.com')
							->addTo($this->register_params['email'])
							->setSubject('Verification process for registering with Your Christian Network')
							->setBody($body_part)
							->setEncoding('UTF-8');
						
						$transport = new Sendmail();
						
						$transport->send($message);
						
						return true;
					} else {
						throw new \Exception("Error processing your request to join, please make sure all of the fields are filled out and try again.");
					}
				}
			} else {
				throw new \Exception("Error processing your request to join.");
			}
		}
	}