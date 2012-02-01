<?php

class ZP_File_Cache extends ZP_Load {
	
	private $file      = NULL;
	private $filename  = NULL;
	private $filePath  = NULL;
	private $groupPath = NULL;	
	private $status    = _cacheStatus;

	public function setUp($host = "www/lib/cache", $port = ".cache", $defaulExpire = 3600){
		if(!defined("_cacheDir")) {
			define("_cacheDir", $host);
		}

		if(!defined("_cacheTime")) {
			define("_cacheTime", $defaulExpire);
		}

		if(!defined("_cacheExt")) {
			define("_cacheExt", $port);
		}
	}
	private function checkExpiration($expirationTime) {
		return (time() < $expirationTime) ? TRUE : FALSE;
	}

	private function checkIntegrity($readHash, $serializedData) {
		$hash = sha1($serializedData);
		
		return ($readHash === $hash) ? TRUE : FALSE;
	}

	private function delete($dir) {
		if(empty($dir)) {
			return FALSE;
		}
		
		if(!file_exists($dir)) {
			return TRUE;
		}
		
		if(!is_dir($dir) or is_link($dir)) {
			return unlink($dir); 
		}
		
		foreach(scandir($dir) as $item) {
			if($item === "." or $item === "..") {
				continue;
			}
			
			if(is_dir($dir . $item)) {
				$this->delete($dir . $item . "/");
			} else {
				unlink($dir . $item);
			}
		}
		
		return rmdir($dir);
	}

	private function setFileRoutes($ID, $groupID) {
		$keyName  = $this->getKey($ID);
		$keyGroup = $this->getKey($groupID);
		
		$levelOne = $keyGroup;
		$levelTwo = substr($keyName, 0, 5);
		
		$this->groupPath = _cacheDir . "/" . $levelOne . "/";
		$this->filePath	 = _cacheDir . "/" . $levelOne . "/" . $levelTwo . "/";
		$this->filename	 = $keyName . _cacheExt;
		$this->file		 = $this->filePath . $this->filename;
	}
	
	private function getKey($ID) {
		return sha1($ID);
	}
	public function get($ID, $groupID = "default") {
		if($this->status) {
			$this->setFileRoutes($ID, $groupID);
			
			if(!file_exists($this->file)) {
				return FALSE;
			}
			
			$meta = file_get_contents($this->filePath . $this->filename);
			$meta = unserialize($meta);
			
			$checkExpiration = $this->checkExpiration($meta["expiration_time"]);
			$checkIntegrity	 = $this->checkIntegrity($meta["integrity"], $meta["data"]);
			
			if($checkExpiration and $checkIntegrity) {
				$data = unserialize($meta["data"]);

				if(is_array($data) and isset($data[0]) and $groupID !== "db") {
					return $data[0];
				}

				return $data;
			} else {
				$this->remove($ID, $groupID);
				
				return FALSE;
			}
		}

		return FALSE;
	}


	public function getStatus() {
		return $this->status;
	}
	
	public function remove($ID, $groupID = "default", $groupLevel = FALSE) {
		$this->setFileRoutes($ID, $groupID);
		
		if($groupLevel and $groupID !== "default") {
			if(!$this->groupPath or empty($this->groupPath)) {
				return FALSE;
			}
			
			return $this->delete($this->groupPath);
		} elseif($this->filePath and !empty($this->filePath)) {
			return $this->delete($this->filePath);
		} else {
			return FALSE;
		}
	}
	
	public function removeAll($groupID = "default") {
		$this->delete(_cacheDir . "/" . $this->getKey($groupID) . "/");
	}

	public function save($data, $ID, $groupID = "default", $time = _cacheTime) {
		if($this->status) {
			$this->setFileRoutes($ID, $groupID);
			
			if(!is_dir($this->filePath)) {
				if(!mkdir($this->filePath, 0777, TRUE)) {
					return FALSE;
				}
			}
			
			if(!is_array($data) and !is_object($data)) {
				$data = array($data);
			}
			
			$data = serialize($data);
			
			$hash = sha1($data);
			
			$meta["expiration_time"] = time() + $time;
			$meta["integrity"]		 = $hash;
			$meta["data"]			 = $data;
			
			$data = serialize($meta);
			
			return file_put_contents($this->file, $data, LOCK_EX);
		}
		
		return FALSE;
	}
	
	public function setStatus($status) {
		$this->status = $status;
	}

}
