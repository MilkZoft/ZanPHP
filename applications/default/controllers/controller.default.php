<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Default_Controller extends ZP_Load {
	
	public $args;
	
	public function __construct() {
	
	}
	
	public function index() {		
		print __("Hi, I'm the default application");
	}
	
	public function prueba() {
		$this->args = func_get_args();
		
		print $this->args[0] . $this->args[1];
	}
}
