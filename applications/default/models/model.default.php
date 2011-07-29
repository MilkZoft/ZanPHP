<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Default_Model extends ZP_Model {
	
	private $route;
	private $table;
	private $primaryKey;
	
	public function __construct() {
		$this->Db = $this->db();
		
		$this->helpers();
		
		$this->table = "contacts";
	}
	
	public function getContacts() {
		$this->Db->select("Name, Email, Phone");
		$this->Db->from($this->table);
		
		$this->Db->fetchMode("array");
		$this->Db->encode(TRUE);
		
		$data = $this->Db->get();
		
		return $data;
	}
	
}
