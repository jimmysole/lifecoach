<?php

	namespace Application\Classes;
	
	use Application\Interfaces\PhotosInterface;
	
	use Laminas\Db\Adapter\Adapter;
	use Laminas\Db\Sql\Insert;
	use Laminas\Db\Sql\Sql;
	use Laminas\Db\TableGateway\TableGateway;
	
	
	class Photos implements PhotosInterface
	{
		public int $set_edit_option;
		public int $user_id;
		public array $photo;
		public array $edits;
		public string $store_name;
		public int $item_id;
		public int $store_id;

		public Sql $sql;

		public Insert $insert;
		
		private \Imagick $imagick;
		
		
		public function __construct(TableGateway $gateway, int $user_id, int $edit, int $item_id, int $store_id, string $store_name, string $photo, array $edits)
		{
			try {
				$this->user_id = $user_id;
				$this->store_name = $store_name;
				$this->item_id = $item_id;
				$this->store_id = $store_id;
				
				$this->sql = new Sql($gateway->getAdapter());
				
				if (!empty($photo)) {
					$this->photo = $photo;
					
					$this->imagick = new \Imagick(realpath(getcwd()) . '/public' . $this->photo);
					
					foreach ($edits as $key => $value) {
						$this->edits[$key] = $value;
					}
					
					$this->setEdit($edit);
				}
			} catch (\Exception $e) {
				echo json_encode(['error' => $e->getMessage()]);
			} catch (\ImagickException $e) {
				echo json_encode(['error' => 'Error preparing your image for editing, please try again.']);
			}
		}
		
		
		public function setEdit(int $edit): PhotosInterface
		{
			if ($edit == 1) {
				// black and white edit was set
				$this->set_edit_option = PhotosInterface::PHOTO_EDITS['black_white'];
				
				return $this;
			} else if ($edit == 2) {
				// enhance edit
				$this->set_edit_option = PhotosInterface::PHOTO_EDITS['enhance'];
				
				return $this;
			} else if ($edit == 3) {
				// sepia image
				$this->set_edit_option = PhotosInterface::PHOTO_EDITS['sepia'];
				
				return $this;
			} else if ($edit == 4) {
				// crop image
				$this->set_edit_option = PhotosInterface::PHOTO_EDITS['crop'];
				
				return $this;
			}  else {
				throw new \Exception("Invalid photo edit option given.");
			}
		}
		
		
		public function cropImage(): PhotosInterface
		{
			if ($this->set_edit_option == 4) {
				$crop_width = is_int($this->edits['crop']['width']) ? $this->edits['crop']['width'] : intval($this->edits['crop']['width']);
				$crop_height = is_int($this->edits['crop']['height']) ? $this->edits['crop']['height'] : intval($this->edits['crop']['height']);
				$x = is_int($this->edits['crop']['x']) ? $this->edits['crop']['x'] : intval($this->edits['crop']['x']);
				$y = is_int($this->edits['crop']['y']) ? $this->edits['crop']['y'] : intval($this->edits['crop']['y']);
				
				$this->imagick->cropImage($crop_width, $crop_height, $x, $y);
			}
			
			return $this;
		}
		
		
		public function enhanceImage(): PhotosInterface
		{
			if ($this->set_edit_option == 2) {
				// use Imagick::enhanceImage to enhance the image
				$this->imagick->enhanceImage();
			}
			
			return $this;
		}
		
		
		public function sepiaImage(): PhotosInterface
		{
			if ($this->set_edit_option == 3) {
				// set the threshold of the sepia edit
				$sepia_threshold = is_float($this->edits['sepia']['threshold']) ? $this->edits['sepia']['threshold'] : floatval($this->edits['sepia']['threshold']);
				
				$this->imagick->sepiaToneImage($sepia_threshold);
			}
			
			return $this;
		}
		
		
		public function blackWhiteImage(): PhotosInterface
		{
			if ($this->set_edit_option == 1) {
				$this->imagick->transformImageColorSpace($this->edits['colorspace']['value']);
				$this->imagick->separateImageChannel($this->edits['colorspace']['channel']);
			}
			
			return $this;
		}
		
		
		public function saveImage(): bool
		{
			$this->imagick->setImageFormat('jpeg');
			
			if (!is_dir(realpath(getcwd()) . '/public/images/uploads/store/' . $this->user_id . '/' . $this->store_name .  '/edited_photos/' . date('Y-m-d-H-i'))) {
				mkdir(realpath(getcwd()) . '/public/images/uploads/store/' . $this->user_id . '/' . $this->store_name . '/edited_photos/' . date('Y-m-d-H-i')  . '/', 0777, true);
			}
			
			// save the image
			$match = preg_match('/\w+(.jpg)$/', $this->photo, $matches);
			
			copy(realpath(getcwd()) .  '/public' . $this->photo, realpath(getcwd()) . '/public/images/uploads/store/' . $this->user_id . '/' . $this->store_name  . '/edited_photos/'  . date('Y-m-d-H-i') . '/' . $matches[0]);
			
			// rename the file to have the edit assigned (e.g. 2019-09-10_1008484_bw_image.jpg)
			// and save the edited file into the edited photos table
			if ($this->set_edit_option == 1) {
				$file = realpath(getcwd()) . '/public/images/uploads/store/' . $this->user_id . '/' . $this->store_name . '/edited_photos/' .  date('Y-m-d-H-i')  . '/'.  rand() . '_' .  'bw' . '_' . $matches[0];
				
				$this->imagick->writeImageFile(fopen($file , 'w'));
				
				$insert = new Insert('edited_items');
				
				$insert->columns(array('item_id', 'store_id', 'edited_image'))
					->values(array('item_id' => $this->item_id, 'store_id' => $this->store_id, 'edited_image' => $file));
				
				$query = $this->sql->getAdapter()->query(
					$this->sql->buildSqlString($insert),
					Adapter::QUERY_MODE_EXECUTE
				);
				
				if ($query->count() > 0) {
					return true;
				} else {
					throw new \Exception("Error saving your image");
				}
			} else if ($this->set_edit_option == 2) {
				$file = realpath(getcwd()) . '/public/images/uploads/store/' . $this->user_id . '/' . $this->store_name . '/edited_photos/' . date('Y-m-d-H-i')   .'/' . rand() . '_' . 'enhanced' . '_' . $matches[0];
				
				$this->imagick->writeImageFile(fopen($file, 'w'));
				
				$insert = new Insert('edited_items');
				
				$insert->columns(array('item_id', 'store_id', 'edited_image'))
					->values(array('item_id' => $this->item_id, 'store_id' => $this->store_id, 'edited_image' => $file));
				
				$query = $this->sql->getAdapter()->query(
					$this->sql->buildSqlString($insert),
					Adapter::QUERY_MODE_EXECUTE
				);
				
				if ($query->count() > 0) {
					return true;
				} else {
					throw new \Exception("Error saving your image");
				}
			} else if ($this->set_edit_option == 3) {
				$file = realpath(getcwd()) . '/public/images/uploads/store/' . $this->user_id . '/' . $this->store_name . '/edited_photos/' .  date('Y-m-d-H-i')  . '/' . rand() . '_' .  'sepia' . '_' . $matches[0];
				
				$this->imagick->writeImageFile(fopen($file , 'w'));
				
				$insert = new Insert('edited_items');
				
				$insert->columns(array('item_id', 'store_id', 'edited_image'))
					->values(array('item_id' => $this->item_id, 'store_id' => $this->store_id, 'edited_image' => $file));
				
				$query = $this->sql->getAdapter()->query(
					$this->sql->buildSqlString($insert),
					Adapter::QUERY_MODE_EXECUTE
				);
				
				if ($query->count() > 0) {
					return true;
				} else {
					throw new \Exception("Error saving your image");
				}
			} else if ($this->set_edit_option == 4) {
				$file = realpath(getcwd()) . '/public/images/uploads/store/' . $this->user_id . '/' . $this->store_name . '/edited_photos/' .  date('Y-m-d-H-i') . '/' . rand() . '_' .  'cropped' . '_' . $matches[0];
				
				$this->imagick->writeImageFile(fopen($file , 'w'));
				
				$insert = new Insert('edited_items');
				
				$insert->columns(array('item_id', 'store_id', 'edited_image'))
					->values(array('item_id' => $this->item_id, 'store_id' => $this->store_id, 'edited_image' => $file));
				
				$query = $this->sql->getAdapter()->query(
					$this->sql->buildSqlString($insert),
					Adapter::QUERY_MODE_EXECUTE
				);
				
				if ($query->count() > 0) {
					return true;
				} else {
					throw new \Exception("Error saving your image");
				}
			} else {
				throw new \Exception("Invalid option given.");
			}
		}
	}