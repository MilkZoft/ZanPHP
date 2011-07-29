<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Default_Controller extends ZP_Load {
	
	public function __construct() {
		$this->application("default");
	}
	
	public function index() {		
		print __("Hi, I'm the default application");
	}
	
	public function contacts() {
		$this->Default_Model = $this->model("Default_Model");
		
		$data = $this->Default_Model->getContacts();
		
		____($data);	
	}
	
	public function getContact($contactID = 0) {
		$this->Agenda_Model = $this->model("Agenda_Model");
	
		$data = $this->Agenda_Model->getContact($contactID);
		
		____($data);
	}
}
