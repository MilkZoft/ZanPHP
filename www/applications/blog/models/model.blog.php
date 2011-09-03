<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Blog_Model extends ZP_Model {
	
	public function __construct() {
		$this->Db = $this->db("mongo");
		
		$this->helpers();
		
		$this->collection = "blog";
		
		$this->language = whichLanguage();
	}
	
	public function getPosts($limit, $skip) {
		$this->Db->collection($this->collection);
		$this->Db->limit($limit);
		$this->Db->skip($skip);
		$this->Db->sort("_id", "DESC");
		
		$data = $this->Db->find();

		return $data;
	}
	
	public function getByDate($limit, $skip, $year = FALSE, $month = FALSE, $day = FALSE) {
		$this->Db->collection($this->collection);
		$this->Db->limit($limit);
		$this->Db->skip($skip);
		$this->Db->sort("_id", "DESC");
		
		if($year and $month and $day) {
			$query = array(
						"Language" 	=> $this->language, 
						"Year" 		=> $year,
						"Month"		=> $month, 
						"Day"		=> $day,
						"State" 	=> "Active"
					);
		} elseif($year and $month) {
			$query = array(
						"Language" 	=> $this->language, 
						"Year" 		=> $year,
						"Month"		=> $month, 
						"State" 	=> "Active"
					);
		} elseif($year) {
			$query = array(
						"Language" 	=> $this->language, 
						"Year" 		=> $year, 
						"State" 	=> "Active"
					);
		}
		
		$data = $this->Db->find($query);
		
		return $data;
	}
}
