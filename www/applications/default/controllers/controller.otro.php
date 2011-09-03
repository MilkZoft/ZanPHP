<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Otro_Controller extends ZP_Controller {
	
	public function __construct() {
		$this->app("default");
		
		$this->Templates = $this->templates();
	}
	
	public function index() {		
		print __("Hi, I'm the otro application");
	}
	
	public function contact($ID = 0) {
		$this->Otro_Model = $this->model("Otro_Model");
		
		$data = $this->Otro_Model->getContact($ID);
		
		____($data);
	}
}
