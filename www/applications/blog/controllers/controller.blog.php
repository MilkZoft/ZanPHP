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
		
		$this->config($this->application);
		
		$this->Blog_Model = $this->model("Blog_Model");
		
		$this->Templates = $this->core("Templates");
		
		$this->Templates->theme(_webTheme);
	}
	
	public function index() {
		if(isLang() and isYear(segment(2)) and isMonth(segment(3)) and isDay(segment(4)) and segment(5) and segment(5) !== _page) {
			$this->nice();
		} elseif(!isLang() and isYear(segment(1)) and isMonth(segment(2)) and isDay(segment(3)) and segment(4) and segment(4) !== _page) {
			$this->nice();
		} elseif(isLang() and isYear(segment(1)) and isMonth(segment(2)) and isDay(segment(3))) {
			$this->day();
		} elseif(!isLang() and isYear(segment(1)) and isMonth(segment(2)) and isDay(segment(3))) {
			$this->day();
		} elseif(isLang() and isYear(segment(1)) and isMonth(segment(2))) {
			$this->month();
		} elseif(!isLang() and isYear(segment(1)) and isMonth(segment(2))) {
			$this->month();
		} elseif(isLang() and isYear(segment(2))) {
			$this->year();
		} elseif(!isLang() and isYear(segment(1))) {
			$this->year();
		} else {
			$this->last();
		}
	}
	
	private function nice() {		
		$this->CSS("posts", $this->application);
		
		if(isLang()) {
			$year  = (isYear(segment(2)))  ? segment(2) : NULL;
			$month = (isMonth(segment(3))) ? segment(3) : NULL;
			$day   = (isDay(segment(4)))   ? segment(4) : NULL; 
			$nice  = segment(5);
			$URL   = _webBase . _sh . _webLang . _sh . _blog . _sh . $year . _sh . $month . _sh . $day . _sh . $nice;
		} else {
			$year  = (isYear(segment(1)))  ? segment(1) : NULL;
			$month = (isMonth(segment(2))) ? segment(2) : NULL;
			$day   = (isDay(segment(3)))   ? segment(3) : NULL; 
			$nice  = segment(4);
			$URL   = _webBase . _sh . _webLang . _sh . _blog . _sh . $year . _sh . $month . _sh . $day . _sh . $nice;
		}
		
		$data = $this->Blog_Model->getPost($nice, $year, $month, $day);
		
		$vars["ID_Post"] = $data[0]["ID_Post"];
		$vars["post"]    = $data[0];
		$vars["URL"] 	 = $URL;					
		
		if($data) {	
			$this->title(decode($data[0]["Title"]));
				
			$vars["view"][0] = $this->view("post", TRUE);		
			
			$this->template("content", $vars);			
		} else {
			$this->template("error404");
		}
		
		$this->render();
	}
	
	private function day() {
		$this->CSS("posts", $this->application);
		$this->CSS("pagination");
		
		if(isLang()) {
			$year  = (isYear(segment(2)))  ? segment(2) : NULL;
			$month = (isMonth(segment(3))) ? segment(3) : NULL;
			$day   = (isMonth(segment(4))) ? segment(4) : NULL;
		} else {
			$year  = (isYear(segment(1)))  ? segment(1) : NULL;
			$month = (isMonth(segment(2))) ? segment(2) : NULL;
			$day   = (isMonth(segment(3))) ? segment(3) : NULL;
		}
			
		$data  = $this->Blog_Model->getByDate(10, 0, $year, $month, $day);	
	
		if($data) {
			$this->title("Blog - ". segment(2) ."/". segment(3) ."/". segment(4));
			
			$vars["posts"] = $data;
			$vars["view"]  = $this->view("posts", TRUE);
			
			$this->template("content", $vars);			
		} else {
			$this->template("error404");
		}
		
		$this->render();		
	}
	
	private function month() {
		$this->CSS("posts", $this->application);
		$this->CSS("pagination");
		
		if(isLang()) {
			$year  = (isYear(segment(2)))  ? segment(2) : NULL;
			$month = (isMonth(segment(3))) ? segment(3) : NULL;
		} else {
			$year  = (isYear(segment(1)))  ? segment(1) : NULL;
			$month = (isMonth(segment(2))) ? segment(2) : NULL;
		}
		
		$data  = $this->Blog_Model->getByDate(10, 0, $year, $month);	

		if($data) {
			$this->title("Blog - ". segment(2) ."/". segment(3));
			
			$vars["posts"] = $data;
			$vars["view"]  = $this->view("posts", TRUE);
			
			$this->template("content", $vars);			
		} else {
			$this->template("error404");
		}
		
		$this->render();		
	}
	
	private function year() {
		$this->CSS("posts", $this->application);
		$this->CSS("pagination");
		
		if(isLang()) {
			$year = (isYear(segment(2))) ? segment(2) : NULL;
		} else {
			$year = (isYear(segment(1))) ? segment(1) : NULL;
		}
			
		$data  = $this->Blog_Model->getByDate(10, 0, $year);	
		
		if($data) {
			$this->title("Blog - ". $year);
			
			$vars["posts"] = $data;
			$vars["view"]  = $this->view("posts", TRUE);
			
			$this->template("content", $vars);			
		} else {
			$this->template("error404");
		}
		
		$this->render();		
	}
	
	private function last() {
		$this->title("Blog");
		
		$this->CSS("posts", $this->application);
		$this->CSS("pagination");
				
		$data = $this->Blog_Model->getPosts(10, 0);
		
		if($data) {			
			$vars["posts"] = $data;
			$vars["view"]  = $this->view("posts", TRUE);
			
			$this->template("content", $vars);
		} else {
			$post  = __("Welcome to") . " ";
			$post .= a(_webName, _webBase) . " ";
			$post .= __("this is your first post, going to your") . " ";
			$post .= a(__("Control Panel"), _webBase . _sh . _webLang . _sh . _blog . _sh . "cpanel") . " ";
			$post .= __("and when you add a new post this post will be disappear automatically, enjoy it!");				
			
			$vars["hello"]    =  __("Hello World");
			$vars["date"]     = now(1);
			$vars["post"]     = $post;
			$vars["comments"] = __("No Comments");				
			$vars["view"]  	  = $this->view("zero", TRUE);
			
			$this->template("content", $vars);				
		}
		
		$this->render();
	}
	
}
