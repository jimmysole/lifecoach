<?php

	
	namespace Application\Model;
	
	use Application\Classes\Contact;
	use Laminas\Db\TableGateway\TableGateway;
	
	
	class ContactModel extends Contact
	{
		protected TableGateway $table_gateway;
		
		public array $data = [];
		
		
		public function __construct(TableGateway $gateway, array $data = array())
		{
			parent::__construct($gateway);
			
			$this->table_gateway = $gateway;
			
			if (count($data, 1) > 0) {
				foreach ($data as $key => $value) {
					$this->data[$key] = $value;
				}
			}
		}
		
		public function handleContact(): bool
		{
			try {
				$this->sendMessage($this->data);
				
				return true;
			} catch (\InvalidArgumentException $e) {
				return false;
			}
		}
	}