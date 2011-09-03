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
	//Database Settings
	define("_dbController", "mysqli");
	define("_dbHost", "localhost");
	define("_dbUser", "root"); 
	define("_dbPwd", "");
	define("_dbName", "YOUR DATABASE");
	define("_dbPort", "5432");
	define("_dbPfx", "zan_");
	
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
	define("_dbPort", "5432");
	define("_dbPfx", "zan_");
	
	//NoSQL Settings
	define("_dbNoSQLHost", "localhost");
	define("_dbNoSQLPort", 27017);
	define("_dbNoSQLUser", ""); 
	define("_dbNoSQLPwd", "");
	define("_dbNoSQLDatabase", "zanphp");
}
