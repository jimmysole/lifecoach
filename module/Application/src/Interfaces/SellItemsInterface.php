<?php
	
	namespace Application\Interfaces;
	
	interface SellItemsInterface
	{
		/**
		 * Lists an item for selling
		 * @param int $item_id
		 * @param int $store_id
		 * @return bool
		 * @throws \Exception
		 */
		public function listItemForSale(int $item_id, int $store_id) : bool;
		
		
		/**
		 * Removes an item from the store for selling
		 * @param int $item_id
		 * @return bool
		 * @throws \Exception
		 */
		public function removeItemForSale(int $item_id) : bool;
		
		
		/**
		 * Freezes an item from buying once the owner has accepted a bid
		 * @param int $item_id
		 * @param int $store_id
		 * @return bool
		 * @throws \Exception
		 */
		public function freezeItem(int $item_id, int $store_id): bool;
		
		
		/**
		 * Unfreezes an item from not being able to make a bid on
		 * @param int $item_id
		 * @param int $store_id
		 * @return bool
		 * @throws  \Exception
		 */
		public function unfreezeItem(int $item_id, int $store_id): bool;
		
		
		/**
		 * Prepares an item for shipping
		 * @param int $item_id
		 * @param int $store_id
		 * @param array $details
		 * @return bool
		 * @throws \Exception
		 */
		public function prepareItemForShipping(int $item_id, int $store_id, array $details): bool;
		
		
		/**
		 * Updates an item to shipped status
		 * @param int $item_id
		 * @param int $store_id
		 * @param array $details
		 * @return bool
		 * @throws \Exception
		 */
		public function shipItem(int $item_id, int $store_id, array $details): bool;
	}
	
