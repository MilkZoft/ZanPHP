<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class CPanel_Controller extends ZP_Controller {
	
	public function __construct() {
		$this->application = $this->app("pages");
		
		$this->Pages_Model = $this->model("Pages_Model");
				
		$this->Templates = $this->core("Templates");
		
		$this->Templates->theme(_webTheme);
		
		$this->helpers();
	}
	
	public function index() {
		print "Soy el CPanel";
	}
	
	public function add() {
		$this->title("Add Page");
		
		$this->CSS("forms");
		
		if(POST("save")) {
			#$this->Pages_Model->save();
			$vars["alert"] = $this->Pages_Model->saveData();
		} elseif(POST("cancel")) {
			redirect(_webBase);
		}
		
		$vars["ID"] 	   = 0;
		$vars["title"]     = isset($save["error"])     ? recoverPOST("title")     : NULL;
		$vars["content"]   = isset($save["error"])     ? recoverPOST("content")   : NULL;
		$vars["principal"] = isset($save["error"])     ? recoverPOST("principal") : NULL;
		$vars["language"]  = isset($save["error"])     ? recoverPOST("language")  : NULL;
		$vars["situation"] = isset($save["situation"]) ? recoverPOST("situation") : NULL;
		$vars["href"]      = _webBase . _sh . _webLang . _sh . $this->application . _sh . "cpanel" . _sh . "add";
		
		$vars["action"]   = "save";
		$vars["view"] = $this->view("add", TRUE, $this->application);
		
		$this->template("content", $vars);
		
		$this->render();
	}
	
	public function delete($ID = 0) {
		if((int) $ID === 0) {
			redirect(_webBase);
		}
		
		$this->Pages_Model->delete($ID);
		
		showAlert("Registro eliminado correctamente", _webBase);
	}
	
	public function edit($ID = 0) {
		if((int) $ID === 0) {
			redirect(_webBase);
		}
		
		$this->title("Add Page");
		
		$this->CSS("forms");
		
		if(POST("edit")) {
			$vars["alert"] = $this->Pages_Model->edit($ID);
		} elseif(POST("cancel")) {
			redirect(_webBase);	
		}
		
		$data = $this->Pages_Model->getByID($ID);
		
		$vars["ID"]        = recoverPOST("ID", $data[0]["ID_Page"]);
		$vars["title"]     = recoverPOST("title", $data[0]["Title"]);
		$vars["content"]   = recoverPOST("content", $data[0]["Content"]);
		$vars["principal"] = recoverPOST("principal", $data[0]["Principal"]);
		$vars["language"]  = recoverPOST("language", $data[0]["Language"]);
		$vars["situation"] = recoverPOST("situation", $data[0]["Situation"]);
		$vars["href"]      = _webBase . _sh . _webLang . _sh . $this->application . _sh . "cpanel" . _sh . "edit" . _sh . $ID;
		
		$vars["action"]   = "edit";
		$vars["view"] = $this->view("add", TRUE, $this->application);
		
		$this->template("content", $vars);
		
		$this->render();
	}
}
