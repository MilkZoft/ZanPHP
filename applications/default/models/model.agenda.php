<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Agenda_Model extends ZP_Model {
	
	private $route;
	private $table;
	private $primaryKey;
	
	public function __construct() {		
		$this->Db = $this->db();
		
		$this->helpers();
		
		$this->table = "contacts";
	}
	
	public function getContact($contactID) {
		$this->Db->encode(TRUE);
		$this->Db->fetchMode("array");
		$this->Db->select("Name, Email, Phone");
		$this->Db->from($this->table);
		$this->Db->where(array("ID_Contact" => $contactID));
		
		$data = $this->Db->get();
		
		return $data;
	}
	
}
