<?php 
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

ob_start(); 
session_start();

define("_dir", dirname(__FILE__));

if(!version_compare(PHP_VERSION, "5.1.0", ">=")) {
	die("ZanPHP needs PHP 5.1.X or higher to run.");
}

if(file_exists(_dir ."/config/config.php")) { 
	include "config/config.php";
} else { 
	die("Error: config.php doesn't exists");
}

if($ZP["production"]) { 
	error_reporting(FALSE);

	ini_set("display_errors", FALSE); 
} else {
	if(!headers_sent()) {
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
		header("Content-type: text/html; charset=utf-8");
	}

	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
}

include _corePath ."/classes/load.php"; 
include _corePath ."/classes/controller.php"; 
include _corePath ."/classes/model.php";