<?php

if(!function_exists("eaccelerator")) {
	die("EAccelerator extension doesn't exists");
}

class ZP_EAccelerator extends ZP_Load {

	public function clear() {
		if(!_cacheStatus) {
			return FALSE;
		}

		eaccelerator_gc();

    	foreach(eaccelerator_list_keys() as $key) {
	    	cache_delete_callback(substr($key["name"], 1));
	    }
	}

	public function fetch($key) {
		return (_cacheStatus) ? eaccelerator_get($key) : FALSE;
	}

	public function store($key, $value, $time = 0) {
		return (_cacheStatus) ? eaccelerator_put($key, $val, $time) : FALSE;
	}

	public function delete($key) {
		return (_cacheStatus) ? eaccelerator_rm($key) : FALSE;
	}

	public function exists($key) {
		return (_cacheStatus) ? in_array($key, eaccelerator_list_keys()) : FALSE;
	}
}