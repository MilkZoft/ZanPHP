<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Agenda_Model extends ZP_Load {
	
	public function __construct() {
		$this->Db = $this->core("Db");
		
		$this->table = "contacts";
	}
	
	public function getContact($contactID) {
		$this->Db->table($this->table);
		$this->Db->encode(TRUE);
		
		$data = $this->Db->find($contactID);
		
		//____($data);
		
		return $data;
	}
	
	public function getContacts() {
		$this->Db->table($this->table);
		//$this->Db->encode(TRUE);
		
		$data = $this->Db->findAll();
		
		return $data;
	}
}