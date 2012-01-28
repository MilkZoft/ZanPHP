<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class CPanel_Controller extends ZP_Controller {
	
	private $vars = array();
	
	public function __construct() {		
		$this->app("cpanel");
		
		$this->application = whichApplication();
				
		$this->Templates = $this->core("Templates");
		
		$this->Templates->theme(_webTheme);

		$this->Polls_Model = $this->model("Polls_Model");
	}
	
	public function index() {
		$this->add();
	}
	
	public function add() {		
		$this->title("Add");
		
		$this->js("add", "polls");	
		
		$Model = ucfirst($this->application) . "_Model";
		
		$this->$Model = $this->model($Model);
		
		if(POST("save")) {
			$this->vars["alert"] = $this->$Model->cpanel("save");
		} elseif(POST("cancel")) {
			redirect(_webBase);
		}
		
		$this->vars["ID"]        = 0;
		$this->vars["title"]     = recoverPOST("title");
		$this->vars["answers"]   = NULL;
		$this->vars["type"] 	 = recoverPOST("type");
		$this->vars["situation"] = recoverPOST("state");
		$this->vars["edit"]      = FALSE;
		$this->vars["action"]	 = "save";
		$this->vars["href"]		 = path("polls" . _sh . "cpanel" . _sh . "add");

		$this->vars["view"] = $this->view("add", TRUE);
		
		$this->template("content", $this->vars);
	}
	
	public function delete($ID = 0) {	
		$this->Polls_Model->delete($ID);
	}
	
	public function edit($ID = 0) {		
		if((int) $ID === 0 and !POST("ID")) { 
			redirect(_webBase . _sh . _webLang . _sh . $this->application . _sh . "cpanel" . _sh . "results");
		} elseif(POST("ID")) {
			$ID = POST("ID");
		}
		
		$this->title("Edit");
		
		$Model = ucfirst($this->application) . "_Model";
		
		$this->$Model = $this->model($Model);
		
		if(POST("edit")) {
			$this->vars["alert"] = $this->$Model->cpanel("edit");
		} elseif(POST("cancel")) {
			redirect(_webBase . _sh . _webLang . _sh . "cpanel");
		} 
	
		$data = $this->$Model->getByID($ID);
		
		if($data) {			
			$this->vars["ID"]  	     = recoverPOST("ID", 	    $data[0]["ID_Poll"]);
			$this->vars["title"]     = recoverPOST("title",     $data[0]["Title"]);
			$this->vars["answers"]   = recoverPOST("answers",   $data[1]);
			$this->vars["type"] 	 = recoverPOST("type",      $data[0]["Type"]);
			$this->vars["situation"] = recoverPOST("situation", $data[0]["Situation"]);
			$this->vars["edit"]      = TRUE;
			$this->vars["action"]	 = "edit";
			$this->vars["href"]		 = path("polls" . _sh . "cpanel" . _sh . "edit" . _sh . $ID);
		
			$this->vars["view"] = $this->view("add", TRUE, $this->application);
			
			$this->template("content", $this->vars);
		} else {
			redirect(_webBase. _sh. _webLang. _sh. $this->application. _sh. "cpanel" . _sh . "results");
		}
	}
	
	public function restore($ID = 0) { 
		if($this->Polls_Model->restore($ID)) {
			redirect(_webBase . _sh . _webLang . _sh . $this->application . _sh . "cpanel" . _sh . _results . _sh . _trash);
		} else {
			redirect(_webBase . _sh . _webLang . _sh . $this->application . _sh . "cpanel" . _sh . _results);
		}
	}
	
	public function results() {	
		$data = $this->Polls_Model->getAllPolls();

		if($data) {
			$vars["polls"] = $data;
			$vars["view"]  = $this->view("results", TRUE);
			
			$this->template("content", $vars);
		} else {
			$this->template("error404");
		}
	}
	
	public function trash($ID = 0) {	
		if($this->CPanel_Model->trash($ID)) {
			redirect(_webBase . _sh . _webLang . _sh . $this->application . _sh . "cpanel" . _sh . _results);
		} else {
			redirect(_webBase . _sh . _webLang . _sh . $this->application . _sh . "cpanel" . _sh . _add);
		}
	}
	
}
