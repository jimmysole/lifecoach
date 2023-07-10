<?php
	
	namespace Application\Interfaces;
	
	
	interface ProfileInterface
	{
		/**
		 * Creates a user profile
		 * @param array $profile_details
		 * @return bool
		 * @throws \Exception
		 */
		public function createProfile(array $profile_details): bool;


        public function uploadProfileAvatar(array $image) : ProfileInterface|bool;


        public function editProfile(array $edits) : ProfileInterface|bool;


        public function deleteProfile() : ProfileInterface|bool;
	}
