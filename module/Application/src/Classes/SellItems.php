<?php
	namespace Application\Classes;
	
	
	use Application\Interfaces\SellItemsInterface;
	
	use Laminas\Db\Adapter\Adapter;
	use Laminas\Db\Sql\Delete;
	use Laminas\Db\Sql\Insert;
	use Laminas\Db\Sql\Update;
	use Laminas\Db\TableGateway\TableGateway;
	use Laminas\Db\Sql\Sql;
	
	
	class SellItems implements SellItemsInterface
	{
		public $table_gateway;
		public $sql;
		
		protected $item_id;
		protected $store_id;
		protected $details = array();
		
		
		public function __construct(TableGateway $gateway)
		{
			$this->table_gateway = $gateway;
			$this->sql = new Sql($this->table_gateway->getAdapter());
		}
		
		
		public function listItemForSale(int $item_id, int $store_id) : bool
		{
			
			$this->item_id = $item_id;
			$this->store_id = $store_id;
			
			$insert = new Insert('items_for_sale');
			
			$insert->columns(array('item_id', 'store_id'))
				->values(array('item_id' => $item_id, 'store_id' => $store_id));
			
			$query = $this->sql->getAdapter()->query(
				$this->sql->buildSqlString($insert),
				Adapter::QUERY_MODE_EXECUTE
			);
			
			if ($query->count() > 0) {
				return true;
			} else {
				throw new \Exception("Error listing your item for sale.");
			}
		}
		
		
		public function removeItemForSale(int $item_id) : bool
		{
			$this->item_id = $item_id;
			
			$delete = new Delete('items');
			
			$delete->where(array('id' => $this->item_id));
			
			$query = $this->sql->getAdapter()->query(
				$this->sql->buildSqlString($delete),
				Adapter::QUERY_MODE_EXECUTE
			);
			
			if ($query->count() > 0) {
				return true;
			} else {
				throw new \Exception("Error removing your item for sale, please try again.");
			}
		}
		
		
		public function freezeItem(int $item_id, int $store_id): bool
		{
			$this->item_id = $item_id;
			$this->store_id = $store_id;
			
			$update = new Update('items_for_sale');
			
			$update->set(array('active' => 0))->where(array('item_id' => $this->item_id, 'store_id' => $store_id));
			
			$query = $this->sql->getAdapter()->query(
				$this->sql->buildSqlString($update),
				Adapter::QUERY_MODE_EXECUTE
			);
			
			if ($query->count() > 0) {
				return true;
			} else {
				throw new \Exception("Error freezing your item for sale, please try again.");
			}
		}
		
		
		public function unfreezeItem(int $item_id, int $store_id): bool
		{
			$this->item_id = $item_id;
			$this->store_id = $store_id;
			
			$query = $this->sql->getAdapter()->getDriver()->getConnection()->execute("SELECT DATEDIFF(shipping_date, NOW()) AS checkdate FROM shipping WHERE shipping_item_id = " . $this->item_id . "
			AND shipping_store_id = " . $this->store_id);
			
			foreach ($query as $row) {
				$result = $row;
			}
			
			if ($result['checkdate'] > 1) {
				$update = new Update('items_for_sale');
				
				$update->set(array('active' => 1))->where(array('item_id' => $this->item_id, 'store_id' => $this->store_id));
				
				$query = $this->sql->getAdapter()->query(
					$this->sql->buildSqlString($update),
					Adapter::QUERY_MODE_EXECUTE
				);
				
				if ($query->count() > 0) {
					// delete the item from the shipping table
					$delete = new Delete('shipping');
					
					$delete->where(array('shipping_item_id' => $this->item_id, 'shipping_store_id' => $this->store_id));
					
					$query = $this->sql->getAdapter()->query(
						$this->sql->buildSqlString($delete),
						Adapter::QUERY_MODE_EXECUTE
					);
					
					if ($query->count() > 0) {
						return true;
					} else {
						throw new \Exception("Error removing your item from the shipping table, please try again.");
					}
				} else {
					throw new \Exception("Error relisting your item for sale, please try again.");
				}
			} else {
				return false;
			}
		}
		
		
		public function prepareItemForShipping(int $item_id, int $store_id, array $details): bool
		{
			$this->item_id = $item_id;
			$this->store_id = $store_id;
			
			if (count($details) > 0) {
				foreach ($details as $key => $value) {
					$this->details[$key] = $value;
				}
				
				// freeze the item first
				try {
					if ($this->freezeItem($this->item_id, $this->store_id)) {
						goto insert;
					}
				} catch (\Exception $e) {
					return false;
				}
				
				insert: {
					// insert the item into the shipping table now
					$insert = new Insert('shipping');
					
					$insert->columns(array('shipping_item_id', 'shipping_store_id', 'shipping_details', 'shipping_status'))
						->values(array('shipping_item_id' => $this->item_id, 'shipping_store_id' => $this->store_id, 'shipping_details' => implode("<br>", $this->details),
							'shipping_status' => $this->details['shipping_status']));
					
					$query = $this->sql->getAdapter()->query(
						$this->sql->buildSqlString($insert),
						Adapter::QUERY_MODE_EXECUTE
					);
					
					if ($query->count() > 0) {
						$insert = new Insert('shipping_messages');
						
						$insert->columns(array('item_id', 'store_id', 'message', 'message_date'))
							->values(array('item_id' => $this->item_id, 'store_id' => $this->store_id, 'message' => 'You\'ve just selected a buyer for your item. You have one day to update the shipping status with a tracking number and carrier
					     otherwise the item will be automatically unfrozen and re-listed for sale.', 'message_date' => date('Y-m-d H:i:s')));
						
						$query = $this->sql->getAdapter()->query(
							$this->sql->buildSqlString($insert),
							Adapter::QUERY_MODE_EXECUTE
						);
						
						if ($query->count() > 0) {
							return true;
						} else {
							throw new \Exception("Error generating the shipping message, please try again.");
						}
					} else {
						throw new \Exception("Error inserting your item into the shipping table, please try again.");
					}
				}
			} else {
				throw new \Exception("You must provided shipping details in order to prepare an item for shipping.");
			}
		}
		
		
		public function shipItem(int $item_id, int $store_id, array $details): bool
		{
			// TODO: Implement shipItem() method.
		}
	}