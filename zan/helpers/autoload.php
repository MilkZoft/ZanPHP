<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

if (!function_exists("__autoload")) {
	function __autoload($class) {	
		$class = strtolower(str_replace("ZP_", "", $class));

		if (file_exists(CORE_PATH ."/classes/". strtolower($class) .".php")) {
			include CORE_PATH ."/classes/". strtolower($class) .".php";	
		}
	}
}