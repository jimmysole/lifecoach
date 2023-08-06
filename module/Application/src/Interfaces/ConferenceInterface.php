<?php
	
	namespace Application\Interfaces;
	
	interface ConferenceInterface
	{
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
