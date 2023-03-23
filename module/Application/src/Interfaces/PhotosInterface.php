<?php
	
	
	namespace Application\Interfaces;
	
	
	interface PhotosInterface
	{
		const PHOTO_EDITS = array(
			'black_white' => 1,
			'enhance'       => 2,
			'sepia'            => 3,
			'crop'              => 4,
		);
		
		/**
		 * Sets the photo edit type
		 * @param int $edit
		 * @return PhotosInterface
		 * @throws \Exception
		 */
		public function setEdit(int $edit): PhotosInterface;
		
		
		/**
		 * Crops an image
		 * @return PhotosInterface
		 * @throws \ImagickException
		 */
		public function cropImage(): PhotosInterface;
		
		
		/**
		 * Enhances an image
		 * @return PhotosInterface
		 * @throws \ImagickException
		 */
		public function enhanceImage(): PhotosInterface;
		
		
		/**
		 * Peforms a sepia edit on a image
		 * @return PhotosInterface
		 * @throws \ImagickException
		 */
		public function sepiaImage(): PhotosInterface;
		
		
		/**
		 * Performs a black and white edit on an image
		 * @return PhotosInterface
		 * @throws \ImagickException
		 */
		public function blackWhiteImage(): PhotosInterface;
		
		
		/**
		 * Saves the photo
		 * @return bool
		 * @throws \ImagickException
		 */
		public function saveImage(): bool;
	}