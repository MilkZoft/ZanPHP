<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Users_Model extends ZP_Model {
	
	public function __construct() {
		$this->Db = $this->db();
		
		$this->helpers();
		
		$this->table = "users";
		
		$this->Data = $this->core("Data");
	}
	
	public function isMember($sessions = FALSE) {
		if(!$sessions) {
			$username = (POST("username")) ? POST("username") 	  : NULL;
			$password = (POST("pwd"))      ? encrypt(POST("pwd")) : NULL;
		} else {
			$username = SESSION("ZanUsername");
			$password = SESSION("ZanPassword");
		}
		
		$data = $this->Db->findBySQL("Username = '$username' AND Pwd = '$password' AND Privilege = 'Member' AND Situation = 'Active'", $this->table);
		
		if(!$data) {
			return array("alert" => getAlert("Incorrect Login"));
		}
		
		return $data;
	}
	
	public function register() {
		$validations = array(
			"username" => "required",
			"pwd"	   => "length:6",
			"email"	   => "email?",
			"exists"   => array(
				"Username" => POST("username"),
			),
			"exists"   => array(
				"Email"	=> POST("email")
			)
		);
		
		$data = array(
			"Pwd"		=> encrypt(POST("pwd")),
			"Privilege" => "Member",
			"Situation" => "Active"
		);
		
		$this->Data->table($this->table);
		
		$this->Data->ignore(array("pwd", "register"));
		
		$data = $this->Data->proccess($data, $validations);
		
		if(isset($data["error"])) {
			return array("error" => TRUE, "alert" => $data["error"]);
		} 
		
		$this->Db->insert($this->table, $data);
		
		return array("alert" => getAlert("The user has been created correctly", "success"));
	}
}
