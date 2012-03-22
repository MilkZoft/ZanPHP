<?php

if(!function_exists("xcache_get")) {
	die("XCache extension doesn't exists");
}

class ZP_XCache extends ZP_Load {

	public function clear() {
		if(!_cacheStatus) {
			return FALSE;
		}

		xcache_clear_cache(XC_TYPE_VAR, 0);
	}

	public function fetch($key) {
		return (_cacheStatus) ? xcache_get($key) : FALSE;
	}

	public function store($key, $value, $time = 3600) {
		return (_cacheStatus) ? xcache_set($key, $value, $time) : FALSE;
	}

	public function delete($key) {
		return (_cacheStatus) ? xcache_unset($key) : FALSE;
	}

	public function exists($key) {
		return (_cacheStatus) ? xcache_isset($key) : FALSE;
	}
}