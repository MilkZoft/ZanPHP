<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

if (!function_exists("_get")) {
	function _get($var = null)
	{
		global $ZP;

		if ($var === "db") {
			include "www/config/database.php";
			return isset($ZP["db"]) ? $ZP["db"] : false;
		}

		return isset($ZP[$var]) ? $ZP[$var] : false;
	}
}

if (!function_exists("set")) {
	function set($var = null, $value = null)
	{
		global $ZP;

		if (is_null($var) or is_null($value)) {
			return false;
		}

		$ZP[$var] = $value;
	}
}