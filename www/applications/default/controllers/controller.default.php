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

		$this->autoRender(FALSE);
	}
	
	public function index() {		
		print "Welcome to ZanPHP";
	}

	public function show($message) {
		$vars["message"] = $message;
		$vars["view"]	 = $this->view("show", TRUE);
		
		#$this->template("content", $vars);
	}

}
