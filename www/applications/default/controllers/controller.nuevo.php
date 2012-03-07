<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Nuevo_Controller extends ZP_Controller {
	
	public function __construct() {
		$this->app("default");
		
		#$this->Default_Model = $this->model("Default_Model");
		
		$this->Templates = $this->core("Templates");
		$this->Templates->theme(_webTheme);
	}
	
	public function index() {		
		print "Hola soy el controlador Nuevo de la aplicacion default";
	}
	
	public function imprime($texto) {
		$vars["message"] = $texto;
		$vars["view"]	 = $this->view("welcome", TRUE);

		$this->template("content", $vars);
	}

}
