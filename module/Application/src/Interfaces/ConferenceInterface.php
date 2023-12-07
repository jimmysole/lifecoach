<?php
	
	namespace Application\Interfaces;
	
	interface ConferenceInterface
	{
        /**
         * Gets all the conferences
         * @return array
         * @throws \Exception
         */
        public function getConferences() : array|bool;

		/**
		 * Sets up a conference
		 * @return ConferenceInterface
		 * @throws \Exception
		 */
		public function startConference() : ConferenceInterface;
		
		/**
		 * Ends a conference
		 * @return ConferenceInterface
		 * @throws \Exception
		 */
		public function endConference() : ConferenceInterface;
		
		
		/**
		 * @param array $details
		 * @return ConferenceInterface
		 * @throws \Exception
		 */
		public function prepareRoom(array $details) : ConferenceInterface;
	}
