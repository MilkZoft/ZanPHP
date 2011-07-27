<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Otro_Controller extends ZP_Load {
	
	public function __construct() {
	
	}
	
	public function index() {		
		print __("Hi, I'm the otro controllador of default application");
	}
	
	public function prueba2($p1, $p2, $p3) {
		print $p3 . $p2 . $p1;	
	}
}
