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
        protected string $user;
		    
        private TableGateway $gateway;
        private Sql $sql;
        private Select $select;

		    
			
		    
        public function __construct(TableGateway $gateway)
        {
            $this->gateway = $gateway;
            $this->sql = new Sql($this->gateway->getAdapter());
            $this->select = new Select();
        }
			

        public function viewAllArticles(): array
        {
            $select = $this->select->columns(['article_id', 'author', 'title', 'subject', 'body', 'date_written', 'image'])->from('articles')->where('id IS NOT NULL');
				
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
            $select = $this->select->columns(['article_id', 'author', 'title', 'subject', 'body', 'date_written', 'image'])->from('articles')->where(['article_id' => $criteria]);
				
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
	}
