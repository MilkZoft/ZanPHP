<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Blog_Model extends ZP_Model {
	
	public function __construct() {
		$this->Db = $this->db();

		$this->helpers();
		
		$this->language = whichLanguage();
		$this->table 	= "blog";
	}
	
	public function getPosts($year = FALSE, $month = FALSE, $day = FALSE, $slug = FALSE) {
		$this->Db->table($this->table);
		
		if($year and $month and $day and $slug) {
			$data = $this->Db->findBySQL("Year = '$year' AND Month = '$month' AND Day = '$day' AND Slug = '$slug'");
		} elseif($year and $month and $day) {
			$data = $this->Db->findBySQL("Year = '$year' AND Month = '$month' AND Day = '$day'");
		} elseif($year and $month) {
			$data = $this->Db->findBySQL("Year = '$year' AND Month = '$month'");
		} elseif($year) {
			$data = $this->Db->findBySQL("Year = '$year'");
		} else {
			$data = $this->Db->findAll(NULL, NULL, "ID_Post DESC", 10);
		}
		
		return $data;
	}
			
}
