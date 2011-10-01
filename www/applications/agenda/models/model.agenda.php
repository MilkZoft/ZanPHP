<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Agenda_Model extends ZP_Model {
	
	public function __construct() {
		$this->Db = $this->db();
		
		$this->helpers();
		
		$this->table = "agenda";
	}
	
	public function getAllContacts() {
		 $data = $this->Db->findAll($this->table);
		 
		 return $data;
	}
	
	public function getContact($contactID) {
		$data = $this->Db->find($contactID, $this->table);
		
		return $data;
	}
	
	public function getContactByEmail($email) {
		$this->Db->table($this->table);
		
		$data = $this->Db->findBy("Email", $email);
		
		return $data;
	}
	
	public function getContactByNameAndPhone($name, $phone) {
		 $data = $this->Db->findBySQL("Name = '$name' AND Phone = '$phone'", $this->table);
		 
		 return $data;
	}
	
}
