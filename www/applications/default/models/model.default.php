<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Default_Model extends ZP_Model {
	
	public function __construct() {
		$this->Db = $this->db();
		
		$this->helpers();
		
		$this->table = "contacts";
	}
	
	public function contact($ID) {
		$this->Db->table("contacts");
		$this->Db->cache(TRUE);
		$this->Db->encode(TRUE);
		$data = $this->Db->find($ID);
		
		return $data;
	}
	
}
