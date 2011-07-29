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
		
		$this->helpers();
	}
	
	public function index() {		
		print __("Hi, I'm the otro controllador of default application");
	}
	
	public function getContact($contactID = 0) {
		$this->Agenda_Model = $this->model("Agenda_Model");
		$data = $this->Agenda_Model->getContact($contactID);
		____($data);
	}
}
