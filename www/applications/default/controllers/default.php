<?php
/**
 * Access from index.php:
 */


class Default_Controller extends ZP_Load {
	
	public function __construct() {
		$this->app("default");
		
		$this->Templates = $this->core("Templates");

		$this->Templates->theme();
	}
	
	public function index() { 
		$vars["message"] = __("Hello World");
		$vars["view"]	 = $this->view("show", TRUE);
		
		$this->render("content", $vars);
	}

	public function test($param1 = "Hola", $param2 = "Adios") {
		print "New dispatcher it's works fine: $param1, $param2";
	}

	public function show($message) {
		$this->title("ZanPHP");
		
		$vars["message"] = $message;
		$vars["view"] = $this->view("show", TRUE);

		$this->render("content", $vars);		
	}

}
