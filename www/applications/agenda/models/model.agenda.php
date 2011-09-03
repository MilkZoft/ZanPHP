<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Agenda_Model extends ZP_Model {
	
	public function __construct() {
		$this->Db = $this->db("mongo");
		
		$this->helpers();
		
		$this->collection = "agenda";
	}
	
	public function add() {
		if(is_null(POST("Name"))) {
			return FALSE;
		} elseif(!isEmail(POST("Email"))) {
			return FALSE;
		}
		
		$data = POST(TRUE);
		
		$this->Db->collection($this->collection);
		
		$this->Db->save($data);
		
		return TRUE;
	}
	
	public function contact($contactID) {
		$this->Db->collection($this->collection);
		
		$data = $this->Db->findByID($contactID);		
		
		return $data;
	}
	
	public function contacts() {
		$this->Db->collection($this->collection);
		
		$data = $this->Db->find();
		
		return $data;
	}
	
	public function edit($contactID, $data) {
		$this->Db->collection($this->collection);
		
		$response = $this->Db->update(array("_id" => new MongoId($contactID)), $data);
		
		return $response;
	}
	
	public function delete($contactID = FALSE) {
		if($contactID) {
			$this->Db->collection($this->collection);

			$response = $this->Db->delete(array("_id" => new MongoId($contactID)));
			
			return $response;
		}
	}
	
	public function save() {
		$this->Db->set("Name", "Carlos");
		$this->Db->set("Email", "carlos@milkzoft.com");
		$this->Db->set("Phone", 232323);
		$this->Db->save();
		
		$this->Db->set("Name", array("Carlos", "Lalo"));
		$this->Db->set("Email", array("carlos@milkzoft.com", "lalo.diabulux@hotmail.com"));
		$this->Db->set("Phone", array("232323", "12345"));
		$this->Db->save();
	}
}
