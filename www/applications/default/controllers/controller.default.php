<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Default_Controller extends ZP_Controller {
	
	public function __construct() {
		$this->app("default");
				
		$this->Templates = $this->core("Templates");

		$this->Templates->theme(_webTheme);
	}
	
	public function index() {		
		$vars["message"] = "Welcome to ZanPHP";
		$vars["view"]	 = $this->view("welcome", TRUE);
		
		$this->template("content", $vars);
	}

	public function test($param1, $param2) {
		print "New dispatcher it's works fine: $param1, $param2";
	}

	public function show($message) {
		$vars["message"] = $message;
		$vars["view"]	 = $this->view("show", TRUE);
		
		$this->template("content", $vars);
		#$this->view("show", $vars);
	}

}
