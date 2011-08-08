<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

/**
 * Database configuration:
 */
$production = FALSE;

if($production) {
	define("_dbController", "mysqli");
	define("_dbHost", "localhost");
	define("_dbUser", "root"); 
	define("_dbPwd", "");
	define("_dbName", "YOUR DATABASE");
	define("_dbPort", "5432");
	define("_dbPfx", "zan_");
} else {
	define("_dbController", "mysqli");
	define("_dbHost", "localhost");
	define("_dbUser", "root"); 
	define("_dbPwd", "");
	define("_dbName", "zanphp");
	define("_dbPort", "5432");
	define("_dbPfx", "zan_");
}
