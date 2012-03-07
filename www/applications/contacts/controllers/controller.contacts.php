<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Contacts_Controller extends ZP_Controller {
	
	public function __construct() {
		$this->app("contacts");
				
		$this->Templates = $this->core("Templates");

		$this->Templates->theme(_webTheme);

		$this->Contacts_Model = $this->model("Contacts_Model");		
	}

	public function contact($contactID) {
		$data = $this->Contacts_Model->getContact($contactID);

		if($data) {
			$vars["contact"] = $data[0];
			$vars["view"]	 = $this->view("contact", TRUE);

			$this->template("content", $vars);
		} else {
			$this->template("error404");
		}
	}

	public function contacts() {
		$data = $this->Contacts_Model->getContacts();
		____($data);
		if($data) {
			$vars["contact"] = $data[0];
			$vars["view"]	 = $this->view("contacts", TRUE);

			$this->template("content", $vars);
		} else {
			$this->template("error404");
		}	
	}

}
