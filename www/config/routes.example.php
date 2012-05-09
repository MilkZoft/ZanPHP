<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

$routes = array(
	0 => array(
			"pattern"	  => "/^test/",
			"application" => "default",
			"controller"  => "default",
			"method"	  => "test",
			"params"	  => array()
		),
	1 => array(
			"pattern"	  => "/^test/",
			"application" => "default",
			"controller"  => "default",
			"method"	  => "test",
			"params"	  => array()
		),
);