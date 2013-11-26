<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

class ZP_Images extends ZP_Load
{
	public $image;
	public $imageType;

	public function crop($rect = array(0, 0, 90, 90), $width = null, $height = null)
	{
		if ($rect === true) {
			$h = $this->getHeight();
			$w = $this->getWidth();

			if ($h > $w) {
				$x = 0;
				$y = ($h - $w) / 2;
				$size = $w;
			} else {
				$x = ($w - $h) / 2;
				$y = 0;
				$size = $h;
			}

			$rect = array($x, $y, $size, $size);
		}

		if (is_null($width)) {
			$width = $rect[2];
		}

		if (is_null($height)) {
			$height = $rect[3];
		}

		$newImage = imagecreatetruecolor($width, $height);
		
		imagecopyresampled($newImage, $this->image, 0, 0, $rect[0], $rect[1], $width, $height, $rect[2], $rect[3]);
		
		$this->image = $newImage;

		return $rect;
	}
	
	public function getHeight()
	{
		return imagesy($this->image);
	}
	
	public function getResize($size, $dir, $filename)
	{	
		$parts = explode(".", $filename);

		if (count($parts > 1)) {
			$filename = $parts[0];
			$extension = $parts[1];
		}

		if ($size === "original") {
			$size = $dir . $filename .".". $extension;
		} elseif ($size === "large") {
			$size = $dir . $filename ."_l.". $extension;
			$this->load($dir . $filename .".". $extension);

			if ($this->getWidth() < $this->getHeight()) {
				$this->resizeToHeight(MIN_LARGE);
			} else {
				$this->resizeToWidth(MAX_LARGE);
			}
		} elseif ($size === "medium") {	
			$size = $dir . $filename ."_m.". $extension;
			$this->load($dir . $filename .".". $extension);

			if ($this->getWidth() < $this->getHeight()) {
				$this->resizeToHeight(MIN_MEDIUM);
			} else {
				$this->resizeToWidth(MAX_MEDIUM);
			}
		} elseif ($size === "small") {
			$size = $dir . $filename ."_s.". $extension;
			$this->load($dir . $filename .".". $extension);

			if ($this->getWidth() < $this->getHeight()) {
				$this->resizeToHeight(MIN_SMALL);
			} else {
				$this->resizeToWidth(MAX_SMALL);
			}
		} elseif ($size === "thumbnail") {
			$size = $dir . $filename . "_t.". $extension;
			
			$this->load($dir . $filename .".". $extension);

			if ($this->getWidth() < $this->getHeight()) {
				$this->resizeToHeight(MIN_THUMBNAIL);
			} else {
				$this->resizeToWidth(MAX_THUMBNAIL);
			}
		}

		$this->save($size);
		
		return $size;
   	}

	public function getWidth()
	{
		return imagesx($this->image);
	}

	public function gif($filename = null)
	{
		if (is_string($filename)) {
			$this->save($filename, IMAGETYPE_GIF);
		} else {
			header("Content-Type: image/gif");

			$this->output(IMAGETYPE_GIF);
		}
	}

	public function jpeg($filename = null)
	{
		if (is_string($filename)) {
			$this->save($filename, IMAGETYPE_JPEG);
		} else {
			header("Content-Type: image/jpeg");

			$this->output(IMAGETYPE_JPEG);
		}
	}
	
	public function load($filename)
	{
		$imageInfo = getimagesize($filename);
		$this->imageType = $imageInfo[2];
	  
		if ($this->imageType === IMAGETYPE_JPEG) {
			$this->image = imagecreatefromjpeg($filename);
		} elseif ($this->imageType === IMAGETYPE_GIF) {
			$this->image = imagecreatefromgif ($filename);
		} elseif ($this->imageType === IMAGETYPE_PNG) {
			$this->image = imagecreatefrompng($filename);
		}
	}
	
	public function output($imageType = IMAGETYPE_JPEG)
	{
		if ($imageType === IMAGETYPE_JPEG) {
			imagejpeg($this->image);
		} elseif ($imageType === IMAGETYPE_GIF) {
			imagegif ($this->image);         
		} elseif ($imageType === IMAGETYPE_PNG) {
			imagepng($this->image);
		}   
	}

	public function png($filename = null)
	{
		if (is_string($filename)) {
			$this->save($filename, IMAGETYPE_PNG);
		} else {
			header("Content-Type: image/png");

			$this->output(IMAGETYPE_PNG);
		}
	}
	
	public function resize($width, $height)
	{
		$newImage = imagecreatetruecolor($width, $height);	
		imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->image = $newImage;   
	}

	public function resizeToHeight($height)
	{
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width, $height);
	}

	public function resizeToWidth($width) 
	{
		$ratio = $width / $this->getWidth();
		$height = $this->getheight() * $ratio;
		$this->resize($width, $height);
	}

	public function save($filename, $imageType = IMAGETYPE_JPEG, $compression = 75, $permissions = null)
	{
		if ($imageType === IMAGETYPE_JPEG) {
			imagejpeg($this->image, $filename, $compression);
		} elseif ($imageType === IMAGETYPE_GIF) {
			imagegif ($this->image, $filename);
		} elseif ($imageType === IMAGETYPE_PNG) {
			imagepng($this->image, $filename);
		}   

		if (!is_null($permissions)) {
			chmod($filename, $permissions);
		}
	}

	public function scale($scale)
	{
		$width = $this->getWidth()  * $scale / 100;
		$height = $this->getheight() * $scale / 100;
		$this->resize($width,$height);
	}

}