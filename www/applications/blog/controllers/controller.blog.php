<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Blog_Controller extends ZP_Controller {
	
	public function __construct() {
		$this->application = $this->app("blog");
				
		$this->Blog_Model = $this->model("Blog_Model");
		
		$this->Templates = $this->core("Templates");
		
		$this->Templates->theme(_webTheme);
	}
	
	public function index() {
		if(isLang() and isYear(segment(2)) and isMonth(segment(3)) and isDay(segment(4)) and segment(5)) {
			$this->slug(segment(2), segment(3), segment(4), segment(5));
		} elseif(isYear(segment(1)) and isMonth(segment(2) and isDay(segment(3) and segment(4)))) {
			$this->slug(segment(1), segment(2), segment(3), segment(4));
		} elseif(isLang() and isYear(segment(2)) and isMonth(segment(3)) and isDay(segment(4))) {
			$this->slug(segment(2), segment(3), segment(4));
		} elseif(isYear(segment(1)) and isMonth(segment(2) and isDay(segment(3)))) {
			$this->slug(segment(1), segment(2), segment(3));
		} elseif(isLang() and isYear(segment(2)) and isMonth(segment(3))) {
			$this->slug(segment(2), segment(3));
		} elseif(isYear(segment(1)) and isMonth(segment(2))) {
			$this->slug(segment(1), segment(2));
		} elseif(isLang() and isYear(segment(2))) {
			$this->slug(segment(2));
		} elseif(isYear(segment(1))) {
			$this->slug(segment(1));
		} else {
			$this->last();
		}
	}
	
	private function last() {
		$data = $this->Blog_Model->getPosts();

		if($data) {
			$vars["posts"] = $data;
			$vars["view"]  = $this->view("posts", TRUE);
			
			$this->template("content", $vars);
		} else {
			$this->template("error404");
		}
		
		$this->render();
	}
	
	private function slug($year = FALSE, $month = FALSE, $day = FALSE, $slug = FALSE) {
		$data = $this->Blog_Model->getPosts($year, $month, $day, $slug);
	
		if($data) {
			$vars["post"] = $data[0];
			$vars["view"] = $this->view("post", TRUE);
			
			$this->template("content", $vars);
		} else {
			$this->template("error404");
		}
		
		$this->render();
	}
}
