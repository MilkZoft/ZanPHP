<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Default_Controller extends ZP_Load {
	
	public function __construct() {
	
	}
	
	public function index() {		
		print __("Hi, I'm the default application");
	}
	
	public function prueba($p1, $p2, $p3) {
		print $p1 . $p2 . $p3;	
	}
}
