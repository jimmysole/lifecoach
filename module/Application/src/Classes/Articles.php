<?php
	
	namespace Application\Classes;
	
	use Application\Interfaces\ArticlesInterface;
	
	
	use Laminas\Db\Adapter\Adapter;
	use Laminas\Db\Sql\Delete;
	use Laminas\Db\Sql\Insert;
	use Laminas\Db\Sql\Select;
	use Laminas\Db\Sql\Sql;
	use Laminas\Db\Sql\Update;
	use Laminas\Db\TableGateway\TableGateway;
	
	
	class Articles implements ArticlesInterface
	{
		    public string $new_article_title;
		    public string $new_article_text;
		    public array $new_article_options = [];
		    
			protected string $user;
		    
		    private TableGateway $gateway;
		    private Sql $sql;
		    private Insert $insert;
		    private Select $select;
		    private Update $update;
		    private Delete $delete;
		    
			
		    
			public function __construct(TableGateway $gateway, string $user)
			{
				$this->user = $user;
				
				$this->gateway = $gateway;
				$this->sql = new Sql($this->gateway->getAdapter());
				$this->insert = new Insert();
				$this->select = new Select();
				$this->update = new Update();
				$this->delete = new Delete();
			}
			
			
			public function writeArticle(string $title, string $text): bool
			{
				$this->new_article_title = !empty($title) ? $title : "Untitled";
				$this->new_article_text = !empty($text) ? $text : "No body written";
				
				
				// have the table categories update the number to reflect the number of articles
				// given to a particular topic (e.g. salvation in categories table would have 2 as the number if there are two articles about salvation)
				
				// first, make sure this isn't a duplicate article
				$select = $this->select->columns(['article_id'])->from('articles')->where(['article_id' => $this->new_article_options['article_id']]);
				
				$query = $this->sql->getAdapter()->query(
					$this->sql->buildSqlString($select),
					Adapter::QUERY_MODE_EXECUTE
				);
				
				if ($query->count() > 0) {
					throw new \Exception("Article already exists.");
				} else {
					// insert the article
					$insert = $this->insert->into('articles')->columns([
						'article_id', 'author', 'title', 'body', 'date_written'
					])->values([
						'article_id' => rand(0, 1000), 'author'=> $this->user, 'title' => $this->new_article_text,
						'body' => $this->new_article_title, 'date_written' => date('Y-m-d H:i:s'),
					]);
					
					$query = $this->sql->getAdapter()->query(
						$this->sql->buildSqlString($insert),
						Adapter::QUERY_MODE_EXECUTE
					);
					
					if ($query->count() > 0) {
						return true;
					} else {
						throw new \Exception("Error entering your article into the database, please try again.");
					}
				}
			}
			
			
			public function modifyArticle(int $id, array $edits = []): bool
			{
				// first check if the article is valid (by checking the id)
				$select = $this->select->columns(['author', 'title', 'body'])->from('articles')->where(['article_id' => $id]);
				
				$query = $this->sql->getAdapter()->query(
					$this->sql->buildSqlString($select),
					Adapter::QUERY_MODE_EXECUTE
				);
				
				if ($query->count() > 0) {
					$row_values = [];
					
					foreach ($query as $key => $value) {
						$row_values = array_merge($row_values, array($key => $value));
					}
					// article found
					// begin the update process
					$updated_author  = empty($edits['author']) ? $row_values['author'] : $edits['author'];
					$updated_title      = empty($edits['title'])     ? $row_values['title']     : $edits['title'];
					$updated_body    = empty($edits['body'])    ? $row_values['body']    : $edits['body'];
					
					$update = $this->update->table('articles')
						->set(['author' => $updated_author, 'title' => $updated_title, 'body' => $updated_body])
						->where(['article_id' => $row_values['article_id']]);
					
					$query = $this->sql->getAdapter()->query(
						$this->sql->buildSqlString($update),
						Adapter::QUERY_MODE_EXECUTE
					);
					
					if ($query->count() > 0) {
						// article updated okay
						return true;
					} else {
						throw new \Exception("Error updating the article, please try again.");
					}
				} else {
					throw new \Exception("Could not locate the article with the supplied id, please try again.");
				}
			}
			
			
			public function removeArticle(int $id): bool
			{
				$delete = $this->delete->from('articles')->where(['article_id' => $id]);
				
				$query = $this->sql->getAdapter()->query(
					$this->sql->buildSqlString($delete),
					Adapter::QUERY_MODE_EXECUTE
				);
				
				if ($query->count() > 0) {
					// article removed
					return true;
				} else {
					throw new \Exception("Error removing article from the database, please try again.");
				}
			}
			
			
			public function viewAllArticles(): array
			{
				$select = $this->select->columns(['article_id', 'author', 'title', 'body', 'date_written'])->from('articles')
                ->where('id IS NOT NULL');
				
				$query = $this->sql->getAdapter()->query(
					$this->sql->buildSqlString($select),
					Adapter::QUERY_MODE_EXECUTE
				);
				
				if ($query->count() > 0) {
					$rows = [];
					
					foreach ($query as $values) {
						$rows[] = $values;
					}
				} else {
					throw new \Exception("No articles were found.");
				}
				
				return $rows;
			}

		
			public function viewArticle(int $criteria): array
			{
				$select = $this->select->columns(['article_id', 'author', 'title', 'body', 'date_written'])->from('articles')->where(['article_id' => $criteria]);
				
				$query = $this->sql->getAdapter()->query(
					$this->sql->buildSqlString($select),
					Adapter::QUERY_MODE_EXECUTE
				);
				
				if ($query->count() > 0) {
					$rows = [];
					
					foreach ($query as $values) {
						$rows[] = $values;
					}
				} else {
					throw new \Exception("No articles were found.");
				}
				
				return $rows;
			}
		
		
		public function __destruct()
			{
				$this->user = null;
			}
	}
