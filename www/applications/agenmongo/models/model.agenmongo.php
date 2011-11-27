<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Agenmongo_Model extends ZP_Model {
	
	public function __construct() {
		$this->Db = $this->db("Mongo");
		
		$this->helpers();
		
		$this->collection = "agenmongo";
	}

	public function add() {
		if(!POST("Name")) {
			return FALSE;
		} elseif(!isEmail(POST("Email"))) {
			return FALSE;
		}
		
		$data = POST(TRUE);
		
		$this->Db->collection($this->collection);
		
		$this->Db->save($data);
		
		return TRUE;
	}

	public function update($contactID, $data) {
 		$this->Db->collection($this->collection);
 
    	$response = $this->Db->update(array("_id" => new MongoId($contactID)), $data);
 
    	return $response;
	}

	public function getAllContacts() {
		$data = $this->Db->findAll($this->collection);

		return $data;
	}	

	public function getContact($contactID) {
		$data = $this->Db->findByID($contactID, $this->collection);

		return $data;
	}

	public function getContactByEmail($email) {
		$data = $this->Db->find(array("Email" => $email), $this->collection);

		return $data;
	}

	public function save($name, $email, $phone) {
		$this->Db->collection($this->collection);
		$this->Db->set("Name", $name);
		$this->Db->set("Email", $email);
		$this->Db->set("Phone", $phone);
		$this->Db->save();

		print "Insertado con exito";
	}
}