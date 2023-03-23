<?php

	namespace Application\Model;
	
	use Laminas\Db\TableGateway\TableGateway;
	
	use Application\Classes\Articles;
	
	
	class ArticleModel
	{
		private TableGateway $gateway;
		private string $article;
		private string $user;
		private int $article_criteria;
		
		private string $title;
		private string $text;
		
		public function __construct(TableGateway $gateway, $user)
		{
			$this->gateway = $gateway;
			$this->user = $user;
			
			$this->article = new Articles($this->gateway, $this->user);
		}
		
		public function postArticle(string $title, string $text) : bool
		{
			$this->title = $title;
			$this->text = $text;
			
			try {
				 $this->article->writeArticle($this->title, $this->text);
			} catch (\Exception $e) {
				return false;
			}
			
			return true;
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