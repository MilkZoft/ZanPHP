<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Agenda_Controller extends ZP_Controller {
	
	public function __construct() {
		$this->app("agenda");

		$this->Agenda_Model = $this->model("Agenda_Model");
				
		$this->Templates = $this->core("Templates");

		$this->Templates->theme(_webTheme);
	}
	
	public function index() {		
		print __("Welcome to Agenda");
	}

	public function add() {
		$vars["view"] = $this->view("add", TRUE);

		if(POST("save")) {
			$vars["alert"] = $this->Agenda_Model->save();
		} 
			
		$this->template("content", $vars);
	}

	public function contact($contactID) {
		$data = $this->Agenda_Model->getContact($contactID);

		____($data);
	}

	public function name($name) {
		$data = $this->Agenda_Model->getContactByName($name);

		____($data);
	}

	public function phoneAndEmail($phone, $email) {
		$data = $this->Agenda_Model->getContactByPhoneAndEmail($phone, $email);

		____($data);
	}

	public function contacts() {
		$data = $this->Agenda_Model->getAllContacts();
		
		$vars["contacts"] = $data;
		$vars["view"] 	  = $this->view("contacts", TRUE);

		$this->template("content", $vars);
	}

}