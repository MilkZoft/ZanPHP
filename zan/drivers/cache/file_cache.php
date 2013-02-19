<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

class ZP_File_Cache extends ZP_Load
{	
	private $file = null;
	private $filename = null;
	private $filePath = null;
	private $groupPath = null;	
	private $status = CACHE_STATUS;

	public function setUp($host = "www/lib/cache", $extension = ".cache", $defaulExpire = 3600)
	{
		if (!defined("CACHE_DIR")) {
			define("CACHE_DIR", $host);
		}

		if (!defined("CACHE_TIME")) {
			define("CACHE_TIME", $defaulExpire);
		}

		if (!defined("CACHE_EXT")) {
			define("CACHE_EXT", $extension);
		}
	}
	private function checkExpiration($expirationTime)
	{
		return (time() < $expirationTime) ? true : false;
	}

	private function checkIntegrity($readHash, $serializedData)
	{
		$hash = sha1($serializedData);
		return ($readHash === $hash) ? true : false;
	}

	private function delete($dir)
	{
		if (empty($dir)) {
			return false;
		}
		
		if (!file_exists($dir)) {
			return true;
		}
		
		if (!is_dir($dir) or is_link($dir)) {
			return unlink($dir); 
		}
		
		foreach (scandir($dir) as $item) {
			if ($item === "." or $item === "..") {
				continue;
			}
			
			if (is_dir($dir . $item)) {
				$this->delete($dir . $item . "/");
			} else {
				unlink($dir . $item);
			}
		}
		
		return rmdir($dir);
	}

	private function setFileRoutes($ID, $groupID)
	{
		$keyName = $this->getKey($ID);
		$keyGroup = $this->getKey($groupID);	
		$levelOne = $keyGroup;
		$levelTwo = substr($keyName, 0, 5);
		
		$this->groupPath = CACHE_DIR ."/". $levelOne ."/";
		$this->filePath	= CACHE_DIR ."/". $levelOne ."/". $levelTwo ."/";
		$this->filename = $keyName . CACHE_EXT;
		$this->file = $this->filePath . $this->filename;
	}
	
	private function getKey($ID)
	{
		return sha1($ID);
	}
	public function get($ID, $groupID = "default")
	{
		if ($this->status) {
			$this->setFileRoutes($ID, $groupID);
			
			if (!file_exists($this->file)) {
				return false;
			}
			
			$meta = file_get_contents($this->filePath . $this->filename);
			$meta = unserialize($meta);
			$checkExpiration = $this->checkExpiration($meta["expiration_time"]);
			$checkIntegrity	= $this->checkIntegrity($meta["integrity"], $meta["data"]);
			
			if ($checkExpiration and $checkIntegrity) {
				$data = unserialize($meta["data"]);

				if (is_array($data) and isset($data[0]) and $groupID !== "db") {
					return $data[0];
				}

				return $data;
			} else {
				$this->remove($ID, $groupID);
				return false;
			}
		}

		return false;
	}

	public function getStatus()
	{
		return $this->status;
	}
	
	public function remove($ID, $groupID = "default", $groupLevel = false)
	{
		$this->setFileRoutes($ID, $groupID);
		
		if ($groupLevel and $groupID !== "default") {
			if (!$this->groupPath or empty($this->groupPath)) {
				return false;
			}
			
			return $this->delete($this->groupPath);
		} elseif ($this->filePath and !empty($this->filePath)) {
			return $this->delete($this->filePath);
		} else {
			return false;
		}
	}
	
	public function removeAll($groupID = "default")
	{
		$this->delete(CACHE_DIR ."/". $this->getKey($groupID) ."/");
	}

	public function save($data, $ID, $groupID = "default", $time = CACHE_TIME)
	{
		if ($this->status) {
			$this->setFileRoutes($ID, $groupID);
			
			if (!is_dir($this->filePath)) {
				if (!mkdir($this->filePath, 0777, true)) {
					return false;
				}
			}
			
			if (!is_array($data) and !is_object($data)) {
				$data = array($data);
			}
			
			$data = serialize($data);
			$hash = sha1($data);
			
			$meta["expiration_time"] = time() + $time;
			$meta["integrity"] = $hash;
			$meta["data"] = $data;
			
			$data = serialize($meta);

			return file_put_contents($this->file, $data, LOCK_EX);
		}
		
		return false;
	}
	
	public function setStatus($status)
	{
		$this->status = $status;
	}

}
