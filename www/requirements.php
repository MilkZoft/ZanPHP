<?php 
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

ob_start(); 
session_start();

defined('__DIR__') or define('__DIR__', dirname(__FILE__));

define("DIR", __DIR__);

if (!version_compare(PHP_VERSION, "5.1.0", ">=")) {
	die("ZanPHP needs PHP 5.1.X or higher to run.");
}

if (file_exists(DIR ."/config/config.php")) { 
	include "config/config.php";
} else { 
	die("Error: config.php doesn't exists");
}

if ($ZP["environment"] > 2) { 
	ini_set("display_errors", false); 
} else {
	if (!headers_sent()) {
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
		header("Content-type: text/html; charset=utf-8");
	}

	error_reporting(E_ALL);
}

include CORE_PATH ."/classes/load.php";