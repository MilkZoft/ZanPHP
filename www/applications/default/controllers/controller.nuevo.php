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
		
		$this->Default_Model = $this->model("Default_Model");
		
		$this->Templates = $this->core("Templates");
	}
	
	public function index() {		
		print "Hola soy el controlador Nuevo de la aplicacion default";
	}
	
	public function imprime($texto) {
		print $texto;
	}

}
