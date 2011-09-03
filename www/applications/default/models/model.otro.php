<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Otro_Model extends ZP_Model {
	
	private $route;
	private $table;
	private $primaryKey;
	
	public function __construct() {
		$this->Db = $this->db();
		
		$this->helpers();
		
		$this->table = "contacts";
	}
	
	public function getContact($ID = 0) {
		$this->Db->select("Name, Email");
		$this->Db->from($this->table);
		
	
		$this->Db->encode(TRUE);
		
		$data = $this->Db->get();
		
		return $data;
	}
	
}
