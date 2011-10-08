<?php
/* ex: set tabstop=2 noexpandtab: */
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
	//Database Settings
	define("_dbController", "mysqli");
	define("_dbHost", "localhost");
	define("_dbUser", "root"); 
	define("_dbPwd", "");
	define("_dbName", "YOUR DATABASE");
	define("_dbPort", "3306");
	define("_dbPfx", "zan_");
	define("_dbSocket", NULL);
	
	//NoSQL Settings
	define("_dbNoSQLHost", "localhost");
	define("_dbNoSQLPort", 27017);
	define("_dbNoSQLUser", ""); 
	define("_dbNoSQLPwd", "");
	define("_dbNoSQLDatabase", "zanphp");
} else {
	//Database Settings
	define("_dbController", "mysqli");
	define("_dbHost", "localhost");
	define("_dbUser", "root"); 
	define("_dbPwd", "");
	define("_dbName", "zanphp");
	define("_dbPort", "3306");
	define("_dbPfx", "zan_");
	define("_dbSocket", NULL);
	
	//NoSQL Settings
	define("_dbNoSQLHost", "localhost");
	define("_dbNoSQLPort", 27017);
	define("_dbNoSQLUser", ""); 
	define("_dbNoSQLPwd", "");
	define("_dbNoSQLDatabase", "zanphp");
}
