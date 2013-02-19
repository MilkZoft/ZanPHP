<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

if (!function_exists("eaccelerator")) {
	die("EAccelerator extension doesn't exists");
}

class ZP_EAccelerator extends ZP_Load
{
	public function clear()
	{
		if (!CACHE_STATUS) {
			return false;
		}

		eaccelerator_gc();

    	foreach (eaccelerator_list_keys() as $key) {
	    	cache_delete_callback(substr($key["name"], 1));
	    }
	}

	public function fetch($key)
	{
		return (CACHE_STATUS) ? eaccelerator_get($key) : false;
	}

	public function store($key, $value, $time = 0)
	{
		return (CACHE_STATUS) ? eaccelerator_put($key, $val, $time) : false;
	}

	public function delete($key)
	{
		return (CACHE_STATUS) ? eaccelerator_rm($key) : false;
	}

	public function exists($key)
	{
		return (CACHE_STATUS) ? in_array($key, eaccelerator_list_keys()) : false;
	}
}