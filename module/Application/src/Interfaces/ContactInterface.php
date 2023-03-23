<?php
	namespace Application\Interfaces;
	
	interface ContactInterface
	{
		public function sendMessage(array $params) : bool;
	}