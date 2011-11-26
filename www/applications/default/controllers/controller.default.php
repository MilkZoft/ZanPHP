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
		print __("Welcome to ZanPHP");
	}

	public function prueba($param1 = NULL, $param2 = NULL) {
		print "Funciona el nuevo sistema de rutas: $param1, $param2";
	}

	public function show($message) {
		$vars["message"] = $message;
		$vars["view"]	 = $this->view("show", TRUE);
		
		$this->template("content", $vars);
	}

}
