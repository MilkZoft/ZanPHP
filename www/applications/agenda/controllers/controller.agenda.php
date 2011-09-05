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
		
		$this->RESTServer = $this->core("RESTServer");
		
		$this->Templates = $this->core("Templates");
		
		$this->Templates->theme(_webTheme);
	}
	
	public function index() {		
		print __("Hi, I'm the default application");
	}
	
	public function add() {
		if($this->RESTServer->isREST(TRUE, "POST")) {
			$data = $this->Agenda_Model->add();
			
			if($data) {
				$message = $this->RESTServer->message("The contact has been inserted correctly");
			} else {
				$message = $this->RESTServer->message("Insert fail", TRUE);
			}
		} else {
			$message = $this->RESTServer->message("Invalid REST request", TRUE);
		}
		
		$this->RESTServer->response($message, FALSE, "POST");
	}
	
	public function contact($contactID = 0) { 
		$data = $this->Agenda_Model->contact($contactID);
		
		if($data) {
			if($this->RESTServer->isREST(4)) {
				$this->RESTServer->process($data, 5);
			} else {
				$vars["contacts"] = $data;
				$vars["view"]	  = $this->view("contacts", TRUE);
				
				$this->template("content", $vars);	
				$this->render();
			}
		} else {
			$this->template("error404");	
			$this->render();			
		}
	}
	
	public function contacts() {
		$data = $this->Agenda_Model->contacts();
		
		if($this->RESTServer->isREST(3)) {
			$this->RESTServer->process($data);	
		} else {
			$vars["contacts"] = $data;
			$vars["view"]	  = $this->view("contacts", TRUE);
			
			$this->template("content", $vars);
			
			$this->render();
		}
	}
	
	public function edit($contactID = 0) {
		if($this->RESTServer->isREST(TRUE, "PUT")) {
			$data = $this->RESTServer->data();
			
			$response = $this->Agenda_Model->edit($contactID, $data);
			
			if($response) {
				$message = $this->RESTServer->message("The contact has been edited correctly");
			} else {
				$message = $this->RESTServer->message("Update Fail", TRUE);
			}
			
			$this->RESTServer->response($message, FALSE, "PUT");
		}
	}
	
	public function delete($contactID = 0) {
		if($this->RESTServer->isREST(TRUE, "DELETE")) {
			$response = $this->Agenda_Model->delete($contactID);
			
			if($response) {
				$message = $this->RESTServer->message("The contact has been deleted correctly");
			} else {
				$message = $this->RESTServer->message("Delete Fail", TRUE);
			}
			
			$this->RESTServer->response($message, FALSE, "DELETE");
		}
	}
}
