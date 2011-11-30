<?php

if(!function_exists("apc_fetch")) {
	die("APC extension doesn't exists");
}

class ZP_APC extends ZP_Load {
	
	private $status = _cacheStatus;

	public function add($key, $value, $time = 0) {
		if(!_cacheStatus) {
			return FALSE;
		}

		apc_add($key, $value, $time);
	}

	public function dump($files = NULL, $vars = NULL) {
		if(_cacheStatus) {
			apc_bin_dump($files, $vars);
		}

		return FALSE;
	}

	public function dumpFile($files, $vars = array(), $filename = NULL, $flags = 0) {
		if(_cacheStatus) {
			apc_bin_dumpfile($files, $vars, $filename, $flags);
		}

		return FALSE;	
	}

	public function load($data = NULL, $flags = 0) {
		if(_cacheStatus) {
			apc_bin_load($data, $flags);	
		} 

		return FALSE;
	}

	public function loadFile($filename) {
		if(_cacheStatus) {
			apc_bin_loadfile($filename);
		} 

		return FALSE;
	}

	public function info($type = "user", $limited = FALSE) {
		if(_cacheStatus) {
			apc_cache_info($type, $limited);
		} 

		return FALSE;
	}
	
	public function cas($key, $old, $new) {
		if(_cacheStatus) {
			apc_cas($key, $old, $new);
		}

		return FALSE;
	}

	public function compile($filename, $atomic = TRUE) {
		if(_cacheStatus) {
			apc_compile_file($filename, $atomic);
		}

		return FALSE;
	}

	public function dec($key, $step = 1, $success = TRUE) {
		if(_cacheStatus) {
			apc_dec($key, $step, $success);
		}

		return FALSE;
	}

	public function constants($key, $constants = array(), $sensitive = TRUE) {
		if(_cacheStatus) {
			apc_define_constants($key, $constants, $sensitive);
		}

		return FALSE;
	}

	public function deleteFile($file) {
		if(_cacheStatus) {
			apc_delete_file($file);
		}

		return FALSE;
	}

	public function inc($key, $step = 1, $success = TRUE) {
		if(_cacheStatus) {
			apc_inc($key, $step, $success);
		}

		return FALSE;
	}

	public function loadConstants($key, $sensitive = TRUE) {
		if(_cacheStatus) {
			apc_load_constants($key, $sensitive);
		}

		return FALSE;
	}

	public function sma($limited = FALSE) {
		if(_cacheStatus) {
			apc_sma_info($limited);
		}

		return FALSE;
	}

	public function clear() {
		if(!_cacheStatus) {
			return FALSE;
		}

		apc_clear_cache("user");
		apc_clear_cache();
	}

	public function fetch($key) {
		return (_cacheStatus) ? apc_fetch($key) : FALSE;
	}

	public function store($key, $value, $time = 0) {
		return (_cacheStatus) ? apc_store($key, $val, $time) : FALSE;
	}

	public function delete($key) {
		return (_cacheStatus) ? apc_delete($key) : FALSE;
	}

	public function exists($key) {
		return (_cacheStatus) ? apc_exists($key) : FALSE;
	}
}