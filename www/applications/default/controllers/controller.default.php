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
		
		$this->Default_Model = $this->model("Default_Model");
		
		$this->Templates = $this->core("Templates");
	}
	
	public function index() {		
		if(isLang() and segment(2) === "otrometodo" and segment(3) > 0 and segment(4) > 0) {
			$this->suma(segment(3), segment(4));
		} elseif(segment(1) === "otrometodo" and segment(2) > 0 and segment(3) > 0)
			$this->suma(segment(2), segment(3));
	}
		
	private function suma($a, $b) {
		print ($a + $b);
	}
	
	public function imprimir($texto) {
		print $texto;
	}

}
