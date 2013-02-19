<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

if (!function_exists("apc_fetch")) {
	die("APC extension doesn't exists");
}

class ZP_APC extends ZP_Load 
{
	private $status = CACHE_STATUS;

	public function add($key, $value, $time = 0) 
	{
		if (!CACHE_STATUS) {
			return false;
		}

		apc_add($key, $value, $time);
	}

	public function dump($files = null, $vars = null)
	{
		if (CACHE_STATUS) {
			apc_bin_dump($files, $vars);
		}

		return false;
	}

	public function dumpFile($files, $vars = array(), $filename = null, $flags = 0)
	{
		if (CACHE_STATUS) {
			apc_bin_dumpfile($files, $vars, $filename, $flags);
		}

		return false;	
	}

	public function load($data = null, $flags = 0)
	{
		if (CACHE_STATUS) {
			apc_bin_load($data, $flags);	
		} 

		return false;
	}

	public function loadFile($filename)
	{
		if (CACHE_STATUS) {
			apc_bin_loadfile($filename);
		} 

		return false;
	}

	public function info($type = "user", $limited = false)
	{
		if (CACHE_STATUS) {
			apc_cache_info($type, $limited);
		} 

		return false;
	}
	
	public function cas($key, $old, $new)
	{
		if (CACHE_STATUS) {
			apc_cas($key, $old, $new);
		}

		return false;
	}

	public function compile($filename, $atomic = true)
	{
		if (CACHE_STATUS) {
			apc_compile_file($filename, $atomic);
		}

		return false;
	}

	public function dec($key, $step = 1, $success = true)
	{
		if (CACHE_STATUS) {
			apc_dec($key, $step, $success);
		}

		return false;
	}

	public function constants($key, $constants = array(), $sensitive = true)
	{
		if (CACHE_STATUS) {
			apc_define_constants($key, $constants, $sensitive);
		}

		return false;
	}

	public function deleteFile($file)
	{
		if (CACHE_STATUS) {
			apc_delete_file($file);
		}

		return false;
	}

	public function inc($key, $step = 1, $success = true)
	{
		if (CACHE_STATUS) {
			apc_inc($key, $step, $success);
		}

		return false;
	}

	public function loadConstants($key, $sensitive = true)
	{
		if (CACHE_STATUS) {
			apc_load_constants($key, $sensitive);
		}

		return false;
	}

	public function sma($limited = false)
	{
		if (CACHE_STATUS) {
			apc_sma_info($limited);
		}

		return false;
	}

	public function clear()
	{
		if (!CACHE_STATUS) {
			return false;
		}

		apc_clear_cache("user");
		apc_clear_cache();
	}

	public function fetch($key)
	{
		return (CACHE_STATUS) ? apc_fetch($key) : false;
	}

	public function store($key, $value, $time = 0)
	{
		return (CACHE_STATUS) ? apc_store($key, $val, $time) : false;
	}

	public function delete($key)
	{
		return (CACHE_STATUS) ? apc_delete($key) : false;
	}

	public function exists($key)
	{
		return (CACHE_STATUS) ? apc_exists($key) : false;
	}
}