<?php
	
	namespace Application\Interfaces;
	
	interface PublicArticlesInterface
	{
		public function postArticle() : PublicArticlesInterface;
		
		public function removeArticle() : PublicArticlesInterface;
		
		public function modifyArticle() : PublicArticlesInterface;
	}
