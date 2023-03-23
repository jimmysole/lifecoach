<?php
	
	namespace Application\Classes;
	
	use Application\Interfaces;
	
	use Laminas\Db\TableGateway;
	use Laminas\Db\Sql\Sql;
	use Laminas\Db\Sql\Select;
	use Laminas\Db\Adapter\Adapter;
	
	
	class Index implements Interfaces\IndexInterface
	{
		
		protected TableGateway\TableGateway $table_gateway;
		protected Sql $sql;
		protected Select $select;
		
		protected array $categories = [];
		protected array $sellers = [];
		protected array $new_stores = [];
		
		
		public function __construct(TableGateway\TableGateway $gateway)
		{
			$this->table_gateway = $gateway;
			
			$this->sql = new Sql($this->table_gateway->getAdapter());
			
			$this->select = new Select();
		}
		
		
		public function listArticles(): array
		{
			$select = $this->select->columns([
				'article_id', 'author', 'title', 'body', 'date_written' ]
			)->from('articles');
			
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
				return [];
			}
			
			return $rows;
		}

	}