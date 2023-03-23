<?php

	namespace Application\Interfaces;
	
	
	interface ArticlesInterface
	{
		/**
		 * Allows for a new article to be written and saved
		 * @param string $title
		 * @param string $text
		 * @return bool
		 * @throws \Exception
		 */
		public function writeArticle(string $title, string $text): bool;
		
		
		/**
		 * Edits an article based on the article id
		 * @param int $id
		 * @param array $edits
		 * @return bool
		 * @throws \Exception
		 */
		public function modifyArticle(int $id, array $edits = []): bool;
		
		
		/**
		 * Removes an article from the database
		 * @param int $id
		 * @return bool
		 * @throws \Exception
		 */
		public function removeArticle(int $id): bool;
		
		
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