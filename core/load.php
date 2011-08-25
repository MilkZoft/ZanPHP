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
include "core/classes/class.controller.php";
include "core/classes/class.model.php";

$Load = new ZP_Load(); 

$helpers = array("i18n", "router", "benchmark", "string", "sessions", "security");

$Load->helper($helpers);

if(_translation === "gettext") {
	$Load->library("class.gettext", "gettext");
	$Load->library("class.streams", "gettext");
	 
	$languageFile = _core . _sh . _languages . _sh . _getText . _sh . _sh . _language . _dot . strtolower(whichLanguage()) . _dot . _mo;

	if(file_exists($languageFile)) {
		$Gettext_Reader = new Gettext_Reader($languageFile);
		
		$Gettext_Reader->load_tables();
	}
}
print __(_("Hello")); die();
#benchMarkStart();

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Content-type: text/html; charset=utf-8");

error_reporting(E_ALL);

if(!version_compare(PHP_VERSION, "5.1.0", ">=")) {
	die("ZanPHP needs PHP 5.1.X or higher to run.");
}

execute();

#print benchMarkEnd();
