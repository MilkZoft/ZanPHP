<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

/**
 * Cache
 */
define("_cacheStatus", FALSE);
define("_cacheDir", "www/lib/cache");
define("_cacheTime", 3600);
define("_cacheExt", ".cache");
