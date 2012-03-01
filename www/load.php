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

if(file_exists(_dir . "/config/config.basics.php") and file_exists(_dir . "/config/config.core.php")) { 
	include "config/config.basics.php";
	include "config/config.core.php";
} else { 
	die("Error: config.basics.php or config.core.php doesn't exists");
}

include _corePath . "/classes/class.load.php";
include _corePath . "/classes/class.controller.php";
include _corePath . "/classes/class.model.php";

$Load = new ZP_Load(); 

$helpers = array("i18n", "router", "benchmark", "exceptions", "string", "sessions", "security");

$Load->helper($helpers);

if(_translation === "gettext") {
	$languageFile = _dir ."/lib/languages/gettext/language.". whichLanguage(TRUE, TRUE) .".mo";
		
	if(file_exists($languageFile)) { 			
		$Load->library("class.streams", NULL, NULL, "gettext");
		$Gettext_Reader = $Load->library("class.gettext", "Gettext_Reader", array($languageFile), "gettext");
		$Load->config("languages");
	
		$Gettext_Reader->load_tables();
	}
}

benchMarkStart();

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Content-type: text/html; charset=utf-8");

error_reporting(E_ALL);

if(!version_compare(PHP_VERSION, "5.1.0", ">=")) {
	die("ZanPHP needs PHP 5.1.X or higher to run.");
}

execute();

#print benchMarkEnd();
