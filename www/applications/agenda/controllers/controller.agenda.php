<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Agenda_Controller extends ZP_Controller {
	
	public function __construct() {
		$this->application = $this->app("agenda");
		
		$this->Agenda_Model = $this->model("Agenda_Model");
				
		$this->Templates = $this->core("Templates");
		
		$this->Templates->theme(_webTheme);
	}
	
	public function contact($contactID) {
		$this->title("Mostrando contacto");
		$this->CSS("default");
		$this->CSS("agenda", $this->application, TRUE);
		
		$data = $this->Agenda_Model->getContact($contactID);
		
		$vars["contact"] = $data[0];
		#$vars["view"]    = $this->view("contact", TRUE);
	
		#$this->template("content", $vars);
		
		#$this->render();
		
		//Cargar una vista directamente sin cargar el theme.
		$this->view("contact", $vars);
	}
	
	public function contacts() {
		$data = $this->Agenda_Model->getAllContacts();
		
		$vars["contacts"] = $data;
		$vars["view"]	  = $this->view("contacts", TRUE);
		
		$this->template("content", $vars);
		
		$this->render();
	}
	
	public function email($email) {
		$data = $this->Agenda_Model->getContactByEmail($email);
		
		____($data);
	}
	
	public function name($name, $phone) {
		$data = $this->Agenda_Model->getContactByNameAndPhone($name, $phone);
		
		____($data);
	}
	
}
