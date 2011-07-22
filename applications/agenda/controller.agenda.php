<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Agenda_Controller extends ZP_Load {
	
	public function __construct() {
		$this->Templates = $this->core("Templates");
		
		$this->Agenda_Model = $this->model("Agenda_Model");
		
		$this->application = "agenda";
		
		$this->Templates->theme(_webTheme);
	}
	
	public function run() {		
		if(segment(2) === "contact" and segment(3)) {
			$this->contact();	
		} else {
			$this->contacts();	
		}
	}
	
	private function contact() {
		$this->title("Contact");
		$this->CSS("contacts", $this->application);
		
		$contactID = segment(3);
		
		$data = $this->Agenda_Model->getContact($contactID);
		
		if($data) {
			$vars["contact"] = $data[0];
			
			//Cargando una vista simple:
			//$this->view("contact", $this->application, $vars);
			
			//Cargando una vista con template:
			$vars["view"] = $this->view("contact", $this->application, TRUE);
			$this->template("content", $vars);
		} else {
			$this->view("error404", $this->application);
			//$this->template("error404");
		}
		
		$this->render();
	}
	
	private function contacts() {
		$this->title("Contacts");
		$this->CSS("contacts2", $this->application);
		
		$data = $this->Agenda_Model->getContacts();
		
		if($data) {
			$vars["contacts"] = $data;
			
			//$this->view("contacts", $this->application, $vars);
			
			//Cargando una vista con template:
			$vars["view"] = $this->view("contacts", $this->application, TRUE);
			$this->template("content", $vars);
		} else {
			//$this->view("error404", $this->application);
			$this->template("error404");
		}
		
		$this->render();
	}
}
