<?php
if(!function_exists("memcache_connect")) {
	die("Memcache extension doesn't exists");
}

class ZP_Memcache extends ZP_Load {

	private $Memcache = NULL;

	public function add($key, $value, $flag = FALSE, $expire = 30) {
		if(!_cacheStatus) {
			return FALSE;
		}

		if(is_object($this->Memcache)) {
			$this->Memcache->add($key, $value, $flag, $expire);

			return TRUE;
		}

		return FALSE;
	}

	public function addServer($host, $port = 11211) {
		if(!_cacheStatus) {
			return FALSE;
		}

		$Memcache = new Memcache;
		
		$Memcache->addServer($host, $port);

		return TRUE;
	}

	public function close() {	    
		if(!_cacheStatus) {
			return FALSE;
		}

	    if(is_object($this->Memcache)) {	    	
			$this->Memcache->close();
	    }

	    return TRUE;	
	}

	public function connect() {	    
		if(!_cacheStatus) {
			return FALSE;
		}

	    if(is_null($this->Memcache)) {
	    	$this->Memcache = new Memcache;

			$this->Memcache->connect(_cacheServer, _cachePort);
	    }

	    return TRUE;	
	}

	public function clear() {
		if(!_cacheStatus) {
			return FALSE;
		}
		
		if(is_object($this->Memcache)) {
			$this->Memcache->flush(); 
	    	
			$time = time() + 1;

	    	while(time() < $time) {}
		}
	}

	public function decrement($key, $value = 1) {
		if(!_cacheStatus) {
			return FALSE;
		}

		return is_object($this->Memcache) ? $this->Memcache->decrement($key, $value) : FALSE;
	}
	
	public function increment($key, $value = 1) {
		return is_object($this->Memcache) ? $this->Memcache->increment($key, $value) : FALSE;
	}

	public function fetch($key) {
		return (_cacheStatus and is_object($this->Memcache)) ? $this->Memcache->get($key) : FALSE;
	}
	
	public function serverStatus($host, $port = 11211) {
		return (_cacheStatus and is_object($this->Memcache)) ? $this->Memcache->getServerStatus($host, $port) : FALSE;
	}

	public function stats($type, $slabid, $limit = 100) {
		return (_cacheStatus and is_object($this->Memcache)) ? $this->Memcache->getStats($type, $slabid, $limit) : FALSE;
	}

	public function version() {
		return (_cacheStatus and is_object($this->Memcache)) ? $this->Memcache->getVersion() : FALSE;
	}
	
	public function pConnect() {
		if(!_cacheStatus) {
			return FALSE;
		}

	    if(is_null($this->Memcache)) {
	    	$this->Memcache = new Memcache;

			$this->Memcache->pconnect(_cacheServer, _cachePort);
	    }

	    return TRUE;
	}
	
	public function replace($key, $value, $flag = FALSE, $expire = 30) {
		return (_cacheStatus and is_object($this->Memcache)) ? $this->Memcache->replace($key, $value, $flag, $expire) : FALSE;
	}
	
	public function set($key, $value, $flag = FALSE, $expire = 30) {
		return (_cacheStatus and is_object($this->Memcache)) ? $this->Memcache->set($key, $value, $flag, $expire) : FALSE;
	}
	
	public function compressThreshold($threshold = 20000, $savings = 0.2) {
		return (_cacheStatus and is_object($this->Memcache)) ? $this->Memcache->setCompressThreshold($threshold, $savings) : FALSE;
	}
	
	public function delete($key) {
		return (_cacheStatus and is_object($this->Memcache)) ? $this->Memcache->delete($key) : FALSE;
	}
	
	public function serverParams($host, $port = 11211, $timeout, $interval = false, $status, $callback) {
		if(_cacheStatus and is_object($this->Memcache)) {
			$this->Memcache->setServerParams($host, $port, $timeout, $interval, $status, $callback);
		}
		
		return FALSE;
	}
}