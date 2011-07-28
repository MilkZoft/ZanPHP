<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Agenda_Model extends ZP_Load {
	
	private $route;
	private $table;
	private $primaryKey;
	
	public function __construct() {
		$this->Db = $this->core("Db");

		$helpers = array("alerts", "time", "string", "security");
		
		$this->helper($helpers);
	
		$this->table = "contacts";
	}
	
	public function getContact($contactID) {
		$this->Db->select("Name, Email, Phone");
		$this->Db->from($this->table);
		$this->Db->where(array("ID_Contact" => $contactID));
		 
		$this->Db->encode(TRUE);
		
		$data = $this->Db->get();
		
		return $data;
	}
	
}
