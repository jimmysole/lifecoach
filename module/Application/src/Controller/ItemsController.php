<?php
	
	namespace Application\Controller;
	
	use Laminas\Mvc\Controller\AbstractActionController;
	
	class ItemsController extends AbstractActionController
	{
		protected $item_service;
		
		
		public function indexAction()
		{
		
		}
		
		
		public function getItemsService()
		{
			if (!$this->item_service) {
				$this->item_service = $this->getServiceLocator()->get('Application\Model\UserModel');
			}
			
			return $this->item_service;
		}
	}
	
