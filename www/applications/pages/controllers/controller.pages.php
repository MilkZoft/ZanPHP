<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Pages_Controller extends ZP_Controller {
	
	public function __construct() {
		$this->application = $this->app("pages");
		
		$this->Pages_Model = $this->model("Pages_Model");
				
		$this->Templates = $this->core("Templates");
		
		$this->Templates->theme(_webTheme);
	}
	
	public function index() {
		$data = $this->Pages_Model->getPrincipal();
		
		if($data) {
			$vars["page"] = $data[0];
			$vars["view"] = $this->view("page", TRUE);
		
			$this->template("content", $vars);
		} else {
			$this->template("error404");
		}
		
		$this->render();
	}
	
	public function page($slug) {
		$data = $this->Pages_Model->getPageBySlug($slug);
		
		if($data) {
			$vars["page"] = $data[0];
			$vars["view"] = $this->view("page", TRUE);
		
			$this->template("content", $vars);
		} else {
			$this->template("error404");
		}
		
		$this->render();
	}
}
