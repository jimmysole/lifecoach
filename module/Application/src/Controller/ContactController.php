<?php

	namespace Application\Controller;
	
	
	use Laminas\Mvc\Controller\AbstractActionController;
	use Laminas\View\Model\ViewModel;
	
	use Application\Model\ContactModel;
	
	class ContactController extends AbstractActionController
	{
		private ContactModel $contact_model;
		
		
		public function __construct(ContactModel $model)
		{
			$this->contact_model = $model;
		}
		
		public function indexAction() : ViewModel
		{
			$layout = $this->layout();
			$layout->setTerminal(true);
			
			$view_model = new ViewModel();
			$view_model->setTerminal(true);
			
			$email = $this->params()->fromPost('email');
			$fname = $this->params()->fromPost('first_name');
			$lname = $this->params()->fromPost('last_name');
			$tel = $this->params()->fromPost('telephone');
			$msg = $this->params()->fromPost('message');
			
			$data = $this->contact_model->data = [
				'email' => $email,
				'first_name' => $fname,
				'last_name'  => $lname,
				'telephone' => $tel,
				'message' => $msg
			];
			
			if ($this->contact_model->sendMessage($data) !== false) {
				echo "Message was sent successfully.";
			}
			
			return $view_model;
		}
	}