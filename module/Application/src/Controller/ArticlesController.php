<?php
	
	namespace Application\Controller;
	
	
	use Application\Model\ArticleModel;
	
	use Laminas\Mvc\Controller\AbstractActionController;
	use Laminas\View\Model\ViewModel;
	
	
	class ArticlesController extends AbstractActionController
	{
		public ArticleModel $article_service;
		
		public ViewModel $view_model;
		
		public function __construct(ArticleModel $article_model)
		{
			$this->article_service = $article_model;
			
			$this->view_model = new ViewModel();
		}
		
		public function indexAction(): ViewModel
		{
			$article_id = $this->params()->fromRoute('article_id');
			
			if (!empty($article_id)) {
				$this->view_model->setVariable('article', $this->article_service->viewArticle($article_id));
			} else {
				try {
					if (count($this->article_service->viewAllArticles(), 1) > 0) {
						$this->view_model->setVariable('articles', $this->article_service->viewAllArticles());
					}
				} catch (\Exception $e) {
					$this->view_model->setVariable('articles', $e->getMessage());
				}
			}
			
			return $this->view_model;
		}
		
		
		public function postArticleAction(): ViewModel
		{
			$layout = $this->layout();
			$layout->setTerminal(true);
			
			$view_model =  new ViewModel();
			$view_model->setTerminal(true);
			
			
			
			return $view_model;
		}
	}