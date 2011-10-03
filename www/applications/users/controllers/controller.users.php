<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Users_Controller extends ZP_Controller {
	
	public function __construct() {
		$this->application = $this->app("users");
		
		$this->Users_Model = $this->model("Users_Model");
				
		$this->Templates = $this->core("Templates");
		
		$this->Templates->theme(_webTheme);
		
		$this->config("users");
	}
	
	public function login() {
		if(POST("login")) {
			$data = $this->Users_Model->isMember();
			
			if(!isset($data["alert"])) {
				print "Bienvenido: ". $data[0]["Username"];
			} else {
				$vars["alert"] = $data;
			}
		} else {
			$vars["view"] = $this->view("login", TRUE);
			
			$this->template("content", $vars);
			
			$this->render();
		}
	}
	
	public function register() {
		if(POST("save")) {
			$vars["alert"] = $this->Users_Model->register();
		} 
		
		$vars["view"] = $this->view("register", TRUE);
			
		$this->template("content", $vars);
			
		$this->render();
	}	
}
