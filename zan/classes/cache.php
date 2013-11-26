<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

class ZP_Cache extends ZP_Load
{
	private $file = null;
	private $filename = null;
	private $filePath = null;
	private $groupPath = null;
	private $status = CACHE_STATUS;
	private $maxIter = 100;

	private function checkExpiration($expirationTime)
	{
		return (time() < $expirationTime) ? true : false;
	}

	private function checkIntegrity($readHash, $serializedData)
	{
		return ($readHash === sha1($serializedData)) ? true : false;
	}

	private function checkUpdating($updateTime)
	{
		return (time() < $updateTime) ? true : false;
	}

	public function data($ID, $group = "default", $Class = false, $method = false, $params = array(), $time = CACHE_TIME)
	{
		if (strtolower(CACHE_DRIVER) == "file") {
			if (CACHE_STATUS and $this->get($ID, $group)) {
				$data = $this->get($ID, $group);

				if (!$data) {
					if (!$Class or !$method) {
						return false;
					}
					
					$data = ($Class) ? call_user_func_array(array($Class, $method), is_array($params) ? $params : array()) : false;

					if (CACHE_STATUS and $data) {
						$this->save($data, $ID, $group, $time);
					}
				}
			} else {
				if (!$Class or !$method) {
					return false;
				}
				
				$data = ($Class) ? call_user_func_array(array($Class, $method), is_array($params) ? $params : array()) : false;
				
				if (CACHE_STATUS and $data) {
					$this->save($data, $ID, $group, $time);
				}
			}
		} elseif (strtolower(CACHE_DRIVER) == "apc") {
			if (CACHE_STATUS and $this->get($ID, $group)) {
				$data = $this->get($ID, $group);

				if (!$data) {
					if (!$Class or !$method) {
						return false;
					}
					
					$data = ($Class) ? call_user_func_array(array($Class, $method), is_array($params) ? $params : array()) : false;

					if (CACHE_STATUS and $data) {
						$this->save($data, $ID, $group, $time);
					}
				}
			} else {
				if (!$Class or !$method) {
					return false;
				}
				
				$data = ($Class) ? call_user_func_array(array($Class, $method), is_array($params) ? $params : array()) : false;
				
				if (CACHE_STATUS and $data) {
					$this->save($data, $ID, $group, $time);
				}
			}
		}

		return $data;
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
			$this->deleteFile($dir); 
		}

		foreach (scandir($dir) as $item) {
			if ($item === "." or $item === "..") {
				continue;
			}

			if (is_dir($dir . $item)) {
				$this->delete($dir . $item . SH);
			} else {
				$this->deleteFile($dir . $item);
			}
		}

		return @rmdir($dir);
	}

	private function deleteFile($filename, $iteration = 0)
	{
		if (is_file($filename) or is_link($filename)) {
			@unlink($filename);

			if (file_exists($filename) and $iteration < $this->maxIter) {
				$this->deleteFile($filename, ++$iteration);
			}
		}
	}

	public function get($ID, $groupID = "default")
	{
		if (strtolower(CACHE_DRIVER) == "file") {
			if ($this->status) {
				$this->setFileRoutes($ID, $groupID);

				if (!file_exists($this->file)) {
					return false;
				}

				$meta = @file_get_contents($this->filePath . $this->filename);
				$meta = @unserialize($meta);
				
				$checkExpiration = $this->checkExpiration($meta["expiration_time"]);
				$checkIntegrity	= $this->checkIntegrity($meta["integrity"], $meta["data"]);

				if ($checkExpiration and $checkIntegrity) {
					return @unserialize($meta["data"]);
				} else {
					return $this->remove($ID, $groupID);
				}
			}

			return false;
		} elseif (strtolower(CACHE_DRIVER) == "apc") {
			if ($data = apc_fetch($ID ."-". $groupID)) {
				return $data;
			} 

			return false;
		}
	}

	private function getKey($ID)
	{
		return sha1($ID);
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function remove($ID, $groupID = "default", $groupLevel = false)
	{
		if (strtolower(CACHE_DRIVER) == "file") {
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
		} elseif (strtolower(CACHE_DRIVER) == "file") {
			apc_delete($ID ."-". $groupID);
		}
	}

	public function removeAll($groupID = "default")
	{
		if (strtolower(CACHE_DRIVER) == "file") {
			$this->delete(CACHE_DIR . SH . $this->getKey($groupID) . SH);
		} elseif (strtolower(CACHE_DRIVER) == "apc") {
			apc_clear_cache("user");
		}
	}

	public function save($data, $ID, $groupID = "default", $time = CACHE_TIME)
	{
		if (strtolower(CACHE_DRIVER) == "file") {
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

				return @file_put_contents($this->file, serialize($meta), LOCK_EX);
			}

			return false;
		} elseif (strtolower(CACHE_DRIVER) == "apc") {
			if ($this->status) { 
				if (!is_array($data) and !is_object($data)) {
					$data = array($data);
				}
				
				return apc_store($ID ."-". $groupID, $data, CACHE_TIME);
			}

			return false;
		}
	}

	private function setFileRoutes($ID, $groupID)
	{
		$keyName  = $this->getKey($ID);
		$keyGroup = $this->getKey($groupID);
		$levelOne = $keyGroup;
		$levelTwo = substr($keyName, 0, 5);
		
		$this->groupPath = CACHE_DIR . SH . $levelOne . SH;
		$this->filePath	= CACHE_DIR . SH . $levelOne . SH . $levelTwo . SH;
		$this->filename	= $keyName . CACHE_EXT;
		$this->file = $this->filePath . $this->filename;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function getValue($ID, $table = "default", $field = "default", $default = false)
	{
		$data = $this->getValues($table, $field);

		if (is_array($data) and isset($data[$ID])) {
			return $data[$ID];
		}

		if ($default === true or !$this->status) {
			$this->Db = $this->db();

			$data = $this->Db->find($ID, $table, $field);
			
			if (isset($data[0][$field])) {
				return $data[0][$field];
			}
		}

		return $default;
	}

	public function getValues($table = "default", $field = "default")
	{
		if ($this->status) {
			$meta = $this->getMetaValue($table, $field);

			return unserialize($meta["data"]);
		}

		return false;
	}

	public function getMetaValue($table = "default", $field = "default")
	{
		if ($this->status) {
			$this->setValueFileRoutes($table, $field);

			if ($content = @file_get_contents($this->file)) {
				$meta = unserialize($content);

				if ($this->checkIntegrity($meta["integrity"], $meta["data"])) {
					if (!$this->checkUpdating($meta["update_time"])) {
						$meta["update_time"] = false;

						$this->Db = $this->db();

						foreach (unserialize($meta["data"]) as $ID => $value) {
							$this->Db->update($table, array($field => $value), $ID);
						}

						$this->deleteFile($this->file);
					}

					return $meta;
				}
			}
		}

		return false;
	}

	public function setValue($ID, $value, $table = "default", $field = "default", $update = false)
	{
		if ($this->status) {
			$this->setValueFileRoutes($table, $field);

			if (!is_dir($this->filePath)) {
				if (!mkdir($this->filePath, 0777, true)) {
					return false;
				}
			}

			$meta = $this->getMetaValue($table, $field);

			if (!$meta) {
				$meta = array();
				$data[$ID] = $value;
				$data = serialize($data);
				$hash = sha1($data);

				if ($update !== false) {
					$meta["update_time"] = time() + $update;
				} else {
					$meta["update_time"] = false;
				}
			} else {
				$data = unserialize($meta["data"]);
				$update_time = $meta["update_time"];
				$data[$ID] = $value;
				$data = serialize($data);
				$hash = sha1($data);

				if ($update !== false and $update_time === false) {
					$meta["update_time"] = time() + $update;
				}
			}

			$meta["integrity"] = $hash;
			$meta["data"] = $data;
			
			$data = serialize($meta);
			@file_put_contents($this->file, $data, LOCK_EX);
		} elseif ($update !== false) {
			$this->Db = $this->db();
			$this->Db->update($table, array($field => $value), $ID);
		}

		return $value;
	}

	public function setValueFileRoutes($table, $field)
	{
		$keyTable = $this->getKey($table);
		$keyField = $this->getKey($field);
		$dirValue = CACHE_DIR . SH ."values";
		
		$this->filePath	= $dirValue . SH . $keyTable . SH;
		$this->filename	= $keyField . CACHE_EXT;
		$this->file = $this->filePath . $this->filename;
	}
}