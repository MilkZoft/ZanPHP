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

#new cache system
if(!defined("_cacheDriver")) {
	define("_cacheDriver", "File");
}
if(!defined("_cacheTime")) {
	define("_cacheTime", 3600); /*yes is used in new cache system*/
}
if(!defined("_cacheHost")) {
	define("_cacheHost", "www/lib/cache");
}
if(!defined("_cachePort")) {
	define("_cachePort", ".cache");
}
if(!defined("_cacheHost")) {
	define("_cacheHost", "localhost"); /*for memcache and other similars*/
}
if(!defined("_cachePort")) {
	define("_cachePort", "11211");/*for memcache and other similars*/
}
