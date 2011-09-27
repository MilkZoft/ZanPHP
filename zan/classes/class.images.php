<?php
/**
 * ZanPHP
 *
 * An open source agile and rapid development framework for PHP 5
 *
 * @package		ZanPHP
 * @author		MilkZoft Developer Team
 * @copyright	Copyright (c) 2011, MilkZoft, Inc.
 * @license		http://www.zanphp.com/documentation/en/license/
 * @link		http://www.zanphp.com
 */
 
/**
 * Access from index.php
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

/**
 * ZanPHP Images Class
 *
 * This class is used to manipulate images
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	classes
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/classes/images_class
 */
class ZP_Images extends ZP_Load {
   
	/**
	 * 
	 * 
	 * 
	 */
	public $image;
	
	/**
	 * 
	 * 
	 * 
	 */
	public $imageType;

    /**
     * 
     *
     * @param string $filename
     * @return void
     */	
	public function getHeight() {
		return imagesy($this->image);
	}
	
 	/**
     * Resize an image into a small, medium and original sizes
	 *
     * @param string $size
     * @param string $dir
     * @param string $filename
     * @param boolean $unlink
     * @param integer $max
     * @param integer $min
     * @return string value
     */
	public function getResize($size, $dir, $filename, $max = 0, $min = 0) {		
		if($size === "original") {
			$size = $dir . strtolower($size) . "_" . $filename;
			
			$this->load($dir . $filename);
			
			if($this->getWidth() < $this->getHeight()) {
				if($min === 0) {
					$this->resizeToWidth(_minOriginal);
				} else {
					$this->resizeToWidth($min);
				}
				
				$this->save($size);				
			} elseif($this->getWidth() > _maxOriginal) {
				
				if($max === 0) {
					$this->resizeToWidth(_maxOriginal);
				} else {
					$this->resizeToWidth($max);
				}
				
				$this->save($size);
			} else {
				$this->resizeToWidth($this->getWidth());
				$this->save($size);				
			}			
		} elseif($size === "medium") {
			$width1 = _minMedium; 
			$width2 = _maxMedium; 
			
			$size = $dir . strtolower($size) . "_" . $filename;
			
			$this->load($dir . $filename);
			
			if($this->getWidth() < $this->getHeight()) {
				$this->resizeToWidth($width1);
				$this->save($size);
			} else {
				$this->resizeToWidth($width2);
				$this->save($size);				
			}		
		} elseif($size === "small") {
			$width1 = _minSmall; 
			$width2 = _maxSmall; 	

			$size = $dir . strtolower($size) . "_" . $filename;
			
			$this->load($dir . $filename);
			
			if($this->getWidth() < $this->getHeight()) {
				$this->resizeToWidth($width1);
				$this->save($size);
			} else {
				$this->resizeToWidth($width2);
				$this->save($size);				
			}			
		} elseif($size === "mini") {
			$width1 = _minMini; 
			$width2 = _maxMini; 	

			$size = $dir . strtolower($size) . "_" . $filename;
			
			$this->load($dir . $filename);
			
			if($this->getWidth() < $this->getHeight()) {
				$this->resizeToWidth($width1);
				$this->save($size);
			} else {
				$this->resizeToWidth($width2);
				$this->save($size);				
			}			
		}
		
		return $size;
   	}
	
    /**
     * 
     *
     * @param string $filename
     * @return void
     */	
	public function getWidth() {
		return imagesx($this->image);
	}

    /**
     * 
     *
     * @param string $filename
     * @return void
     */	
	public function load($filename) {
		$image_info = getimagesize($filename);
	  
		$this->imageType = $image_info[2];
	  
		if($this->imageType === IMAGETYPE_JPEG) {
		 $this->image = imagecreatefromjpeg($filename);
		} elseif($this->imageType === IMAGETYPE_GIF) {
		 $this->image = imagecreatefromgif($filename);
		} elseif($this->imageType === IMAGETYPE_PNG) {
		 $this->image = imagecreatefrompng($filename);
		}
	}
	
    /**
     * 
     *
     * @param string $filename
     * @return void
     */	
	public function output($imageType = IMAGETYPE_JPEG) {
		if($imageType === IMAGETYPE_JPEG) {
			imagejpeg($this->image);
		} elseif($imageType === IMAGETYPE_GIF) {
			imagegif($this->image);         
		} elseif($imageType === IMAGETYPE_PNG) {
			imagepng($this->image);
		}   
	}
	
    /**
     * 
     *
     * @param string $filename
     * @return void
     */	
	public function resize($width, $height) {
		$new_image = imagecreatetruecolor($width, $height);
		
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		
		$this->image = $new_image;   
	}

    /**
     * 
     *
     * @param string $filename
     * @return void
     */	
	public function resizeToHeight($height) {
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		
		$this->resize($width, $height);
	}

    /**
     * 
     *
     * @param string $filename
     * @return void
     */	
	public function resizeToWidth($width) {
		$ratio  = $width / $this->getWidth();
		$height = $this->getheight() * $ratio;
		
		$this->resize($width, $height);
	}
	
    /**
     * 
     *
     * @param string $filename
     * @return void
     */	
	public function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 75, $permissions = NULL) {
		if($image_type === IMAGETYPE_JPEG) {
			imagejpeg($this->image, $filename, $compression);
		} elseif($image_type === IMAGETYPE_GIF) {
			imagegif($this->image, $filename);         
		} elseif($image_type === IMAGETYPE_PNG) {
			imagepng($this->image, $filename);
		}   

		if(!is_null($permissions)) {
			chmod($filename, $permissions);
		}
	}

    /**
     * 
     *
     * @param string $filename
     * @return void
     */	
	public function scale($scale) {
		$width  = $this->getWidth()  * $scale / 100;
		$height = $this->getheight() * $scale / 100; 
		
		$this->resize($width,$height);
	}
	
}
