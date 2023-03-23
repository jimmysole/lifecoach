<?php

	namespace Application\Classes;
 
	use Application\Interfaces\PublicArticlesInterface;
	
	use Laminas\Db\Adapter\Adapter;
	use Laminas\Db\Sql\Delete;
	use Laminas\Db\Sql\Insert;
	use Laminas\Db\Sql\Select;
	use Laminas\Db\Sql\Sql;
	use Laminas\Db\Sql\Update;
	use Laminas\Db\TableGateway\TableGateway;
	use InvalidArgumentException;
	
	class PublicArticles implements PublicArticlesInterface
	{
		public string $title;
		public string $text;
		public bool $comments = false;
		public int $article_id;
		public array $edits = [];
		
		
		private TableGateway $gateway;
		private Sql $sql;
		private Insert $insert;
		private Select $select;
		private Update $update;
		private Delete $delete;
		
		
		public function __construct(TableGateway $gateway, string $title, string $text, int $article_id, bool $comments = false, array $edits = [])
		{
			$this->title = !empty($title) ? $title : "Default Title Article ";
			$this->text = !empty($text) ? $text : "No text was provided.";
			$comments === true ? $this->comments = true : $this->comments = false;
			$this->article_id = $article_id;
			$this->edits = count($edits) > 0 ? array_merge($edits, $this->edits) : [];
			
			$this->gateway = $gateway;
			$this->sql = new Sql($this->gateway->getAdapter());
			$this->insert = new Insert();
			$this->select = new Select();
			$this->update = new Update();
			$this->delete = new Delete();
		}
		
		public function postArticle(): PublicArticlesInterface
		{
			// first lets see if there is an article that already exists
			// don't want to have duplicate articles
			$select = $this->select->columns(['id'])->from('articles')->where(['article_id' => $this->article_id])->limit(1);
			
			$query = $this->sql->getAdapter()->query(
				$this->sql->buildSqlString($select),
				Adapter::QUERY_MODE_EXECUTE
			);
			
			if ($query->count() > 0) {
				throw new InvalidArgumentException("Article already exists");
			} else {
				// cool, the article doesn't exist
				// save it
				$insert = $this->insert->into('articles')->columns(['article_id', 'title', 'body', 'date_written'])
					->values(['article_id' => rand(0, 1000), 'title' => $this->title, 'body' => $this->text, 'date_written' => 'NOW()']);
				
				$query = $this->sql->getAdapter()->query(
					$this->sql->buildSqlString($insert),
					Adapter::QUERY_MODE_EXECUTE
				);
				
				if ($query->count() > 0) {
					// all good
					return $this;
				} else {
					throw new InvalidArgumentException("Error inserting your article, please try again.");
				}
			}
		}
		
		
		public function modifyArticle(): PublicArticlesInterface
		{
			// select the id of the article we wish to modify
			$select = $this->select->columns(['id'])->from('articles')->where(['article_id' => $this->article_id])->limit(1);
			
			$query = $this->sql->getAdapter()->query(
				$this->sql->buildSqlString($select),
				Adapter::QUERY_MODE_EXECUTE
			);
			
			if ($query->count() > 0) {
				// article found
				// modify it with the new info
				$new_data = [
					'title' => $this->title,
					'body' => $this->text,
					'date_written' => 'NOW()',
				];
				
				$update = $this->update->set($new_data)->where('article_id = ' . $this->article_id);
				
				$query = $this->sql->getAdapter()->query(
					$this->sql->buildSqlString($update),
					Adapter::QUERY_MODE_EXECUTE
				);
				
				if ($query->count() > 0) {
					return $this;
				} else {
					throw new InvalidArgumentException("Error modifying your article, please try again.");
				}
			} else {
				throw new InvalidArgumentException("Error locating the article with the info provided, try again.");
			}
		}
		
		
		public function removeArticle(): PublicArticlesInterface
		{
			// get the article id
			$select = $this->select->columns(['id'])->from('articles')->where(['article_id' => $this->article_id])->limit(1);
			
			$query = $this->sql->getAdapter()->query(
				$this->sql->buildSqlString($select),
				Adapter::QUERY_MODE_EXECUTE
			);
			
			if ($query->count() > 0) {
			    // selected, now remove
				$delete = $this->delete->from('articles')->where(['article_id' => $this->article_id]);
				
				$query = $this->sql->getAdapter()->query(
					$this->sql->buildSqlString($delete),
					Adapter::QUERY_MODE_EXECUTE
				);
				
				if ($query->count() > 0) {
					return $this;
				} else {
					throw new InvalidArgumentException("Error removing the article, please check your info and try again.");
				}
			} else {
				throw new InvalidArgumentException("Error locating the article, please check your information and try again.");
			}
		}
	}   