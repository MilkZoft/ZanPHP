<?php 
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

include "requirements.php";

$Load = new ZP_Load(); 

$helpers = array("alerts", "config", "debugging", "exceptions", "i18n", "router", "benchmark", "string", "sessions", "security");

$Load->helper($helpers); 

include "configuration.php";

if($ZP["benchMark"]) {
	benchMarkStart();
}

execute();

if($ZP["benchMark"]) {
	benchMarkEnd();
}