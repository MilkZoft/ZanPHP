<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

if (!function_exists("memcache_connect")) {
	die("Memcache extension doesn't exists");
}

class ZP_Memcache extends ZP_Load
{
	private $Memcache = null;

	public function add($key, $value, $flag = false, $expire = 30)
	{
		if (!CACHE_STATUS) {
			return false;
		}

		if (is_object($this->Memcache)) {
			$this->Memcache->add($key, $value, $flag, $expire);
			return true;
		}

		return false;
	}

	public function addServer($host, $port = 11211)
	{
		if (!CACHE_STATUS) {
			return false;
		}

		$Memcache = new Memcache;	
		$Memcache->addServer($host, $port);
		return true;
	}

	public function close() 
	{
		if (!CACHE_STATUS) {
			return false;
		}

	    if (is_object($this->Memcache)) {	    	
			$this->Memcache->close();
	    }

	    return true;	
	}

	public function connect()
	{
		if (!CACHE_STATUS) {
			return false;
		}

	    if (is_null($this->Memcache)) {
	    	$this->Memcache = new Memcache;
			$this->Memcache->connect(_cacheServer, _cachePort);
	    }

	    return true;	
	}

	public function clear()
	{
		if (!CACHE_STATUS) {
			return false;
		}
		
		if (is_object($this->Memcache)) {
			$this->Memcache->flush(); 
			$time = time() + 1;
	    	while(time() < $time) {}
		}
	}

	public function decrement($key, $value = 1)
	{
		if (!CACHE_STATUS) {
			return false;
		}

		return is_object($this->Memcache) ? $this->Memcache->decrement($key, $value) : false;
	}
	
	public function increment($key, $value = 1)
	{
		return is_object($this->Memcache) ? $this->Memcache->increment($key, $value) : false;
	}

	public function fetch($key)
	{
		return (CACHE_STATUS and is_object($this->Memcache)) ? $this->Memcache->get($key) : false;
	}
	
	public function serverStatus($host, $port = 11211)
	{
		return (CACHE_STATUS and is_object($this->Memcache)) ? $this->Memcache->getServerStatus($host, $port) : false;
	}

	public function stats($type, $slabid, $limit = 100)
	{
		return (CACHE_STATUS and is_object($this->Memcache)) ? $this->Memcache->getStats($type, $slabid, $limit) : false;
	}

	public function version()
	{
		return (CACHE_STATUS and is_object($this->Memcache)) ? $this->Memcache->getVersion() : false;
	}
	
	public function pConnect()
	{
		if (!CACHE_STATUS) {
			return false;
		}

	    if (is_null($this->Memcache)) {
	    	$this->Memcache = new Memcache;
			$this->Memcache->pconnect(_cacheServer, _cachePort);
	    }

	    return true;
	}
	
	public function replace($key, $value, $flag = false, $expire = 30)
	{
		return (CACHE_STATUS and is_object($this->Memcache)) ? $this->Memcache->replace($key, $value, $flag, $expire) : false;
	}
	
	public function set($key, $value, $flag = false, $expire = 30)
	{
		return (CACHE_STATUS and is_object($this->Memcache)) ? $this->Memcache->set($key, $value, $flag, $expire) : false;
	}
	
	public function compressThreshold($threshold = 20000, $savings = 0.2)
	{
		return (CACHE_STATUS and is_object($this->Memcache)) ? $this->Memcache->setCompressThreshold($threshold, $savings) : false;
	}
	
	public function delete($key)
	{
		return (CACHE_STATUS and is_object($this->Memcache)) ? $this->Memcache->delete($key) : false;
	}
	
	public function serverParams($host, $port = 11211, $timeout, $interval = false, $status, $callback)
	{
		if (CACHE_STATUS and is_object($this->Memcache)) {
			$this->Memcache->setServerParams($host, $port, $timeout, $interval, $status, $callback);
		}
		
		return false;
	}
}