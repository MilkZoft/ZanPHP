<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

$routes = array(
	0 => array(
		"pattern"	  => "/^test/",
		"application" => "default",
		"controller"  => "default",
		"method"	  => "test",
		"params"	  => array("Hi", "Goodbye")
	),
);