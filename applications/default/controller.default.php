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
	
	public function run() {		
		print __("Hi, I'm the default application");
	}
}
