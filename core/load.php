<?php 
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

ob_start(); 
session_start(); 

if(file_exists("core/config/config.constants.php")) { 
	include "core/config/config.constants.php";
} else { 
	die("Error: config.constants.php doesn't exists");
}

include "core/classes/class.load.php";

$Load = new ZP_Load(); 

$helpers = array("i18n", "router", "benchmark", "string", "sessions", "security");

$Load->helper($helpers);

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Content-type: text/html; charset=utf-8");

error_reporting(E_ALL);

if(!version_compare(PHP_VERSION, "5.2.0", ">=")) {
	die("ZanPHP needs PHP 5.2.X or higher to run.");
}

execute();
