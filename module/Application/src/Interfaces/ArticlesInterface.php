<?php

	namespace Application\Interfaces;
	
	
	interface ArticlesInterface
	{

		/**
		 * Fetches all articles
		 * @return array
		 * @throws \Exception
		 */
		public function viewAllArticles(): array;
		
		/**
		 * Fetches articles based on criteria
		 * @param int $criteria
		 * @return array
		 * @throws \Exception
		 */
		public function viewArticle(int $criteria): array;
	}