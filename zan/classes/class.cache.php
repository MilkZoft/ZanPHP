<?php

class ZP_Cache extends ZP_Load {
	
	private $status    = _cacheStatus;

	public function __construct() {

		$this->config("cache");
		
		$this->setCache();	
	}
	
	private function setCache(){
		$driverName = _cacheDriver ."_Cache";
		/*TODO: crear sistema para que $driverName siempre tenga las capitalisaciones correctas*/
		$this->driver = $this->driver($driverName, "cache");
		$this->driver->setUP(_cacheHost, _cachePort, _cacheTime);
	}
	
	public function get($ID, $groupID = "default") {
		return $this->driver->get($ID, $groupID);
	}
	
	public function getStatus() {
		return $this->driver->getStatus();
	}
	
	public function remove($ID, $groupID = "default", $groupLevel = FALSE) {
		return $this->driver->remove($ID, $groupID, $groupLevel);
	}
	
	public function removeAll($groupID = "default") {
		return $this->driver->removeAll($groupID);
	}

	public function save($data, $ID, $groupID = "default", $time = _cacheTime) {
		return $this->driver->save($data, $ID, $groupID, $time);
	}
	
	public function setStatus($status) {
		return $this->driver->setStatus($status);
	}

}
