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

	public function save() {
		if(!POST("name")) {
			return getAlert("Error falta el nombre");
		} elseif(!isEmail(POST("email"))) {
			return getAlert("Error, email invalido");
		} elseif(!POST("phone")) {
			return getAlert("Error falta telefono");
		}

		$data = array(
			"Name"  => POST("name"),
			"Email" => POST("email"),
			"Phone" => POST("phone")
		);
		
		$this->Db->insert($this->table, $data);

		return getAlert("Nuevo contacto agregado con &eacute;xito", "success");
	}

	public function getContact($contactID) {
		$data = $this->Db->find($contactID, $this->table);

		return $data;
	}

	public function getContactByName($name) {
		$data = $this->Db->findBy("Name", $name, $this->table);

		return $data;
	}

	public function getContactByPhoneAndEmail($phone, $email) {
		$data = $this->Db->findBySQL("Phone = '$phone' AND Email = '$email'", $this->table);

		return $data;
	}

	public function getAllContacts() {
		$data = $this->Db->findAll($this->table);

		return $data;
	}
		
}