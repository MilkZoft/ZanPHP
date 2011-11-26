<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Agenmongo_Controller extends ZP_Controller {
	
	public function __construct() {
		$this->app("agenmongo");

		$this->Agenmongo_Model = $this->model("Agenmongo_Model");
				
		$this->Templates = $this->core("Templates");

		$this->Templates->theme(_webTheme);
	}
	
	public function index() {		
		print __("Welcome to Agenda");
	}

	public function contacts() {
		$data = $this->Agenmongo_Model->getAllContacts();
		
		____($data);
	}

	public function contact($contactID) {
		$data = $this->Agenmongo_Model->getContact($contactID);

		____($data);
	}

	public function email($email) {
		$data = $this->Agenmongo_Model->getContactByEmail($email);

		____($data);
	}

	public function save($name, $email, $phone) {
		$this->Agenmongo_Model->save($name, $email, $phone);
	}

}