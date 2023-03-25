<?php

	namespace Application\Model;
	
	use Laminas\Db\TableGateway\TableGateway;
	
	use Application\Classes\Articles;
	
	
	class ArticleModel
	{
		private TableGateway $gateway;
		private Articles $article;
		private string $user;
		private int $article_criteria;
		
		private string $title;
		private string $text;
		
		public function __construct(TableGateway $gateway)
		{
			$this->gateway = $gateway;
			
			$this->article = new Articles($this->gateway);
		}

		public function viewAllArticles() : array
		{
			return $this->article->viewAllArticles();
		}
		
		public function viewArticle(int $criteria): array
		{
			$this->article_criteria = $criteria;
			
			return $this->article->viewArticle($this->article_criteria);
		}
	}