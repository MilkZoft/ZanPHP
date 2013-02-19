<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

class Default_Model extends ZP_Load
{
	public function __construct()
	{
		$this->Db = $this->db();
		
		$this->table = "contacts";
		$this->fields = "ID_Contact, Name, Email, Phone";
	}

	public function getContact($contactID)
	{
		return $this->Db->find($contactID, $this->table, $this->fields);
	}
}