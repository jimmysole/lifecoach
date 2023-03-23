<?php
	
	namespace Application\Classes;
	
	
	use Application\Interfaces\ProfileInterface;
	
	
	class Profile implements ProfileInterface
	{
		
		private $profile_details = [];
		
		public function createProfile(array $profile_details): bool
		{
			if (count($profile_details, 1) <= 0) {
				throw new \Exception("User profile details must be filled out.");
			}
			
 			foreach ($profile_details as $key => $value) {
 				$this->profile_details[$key] = $value;
		    }
 			
 			
			
			return true;
		}
	}
