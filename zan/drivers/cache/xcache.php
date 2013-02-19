<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

if (!function_exists("xcache_get")) {
	die("XCache extension doesn't exists");
}

class ZP_XCache extends ZP_Load
{
	public function clear()
	{
		if (!CACHE_STATUS) {
			return false;
		}

		xcache_clear_cache(XC_TYPE_VAR, 0);
	}

	public function fetch($key)
	{
		return (CACHE_STATUS) ? xcache_get($key) : false;
	}

	public function store($key, $value, $time = 3600)
	{
		return (CACHE_STATUS) ? xcache_set($key, $value, $time) : false;
	}

	public function delete($key)
	{
		return (CACHE_STATUS) ? xcache_unset($key) : false;
	}

	public function exists($key)
	{
		return (CACHE_STATUS) ? xcache_isset($key) : false;
	}
}