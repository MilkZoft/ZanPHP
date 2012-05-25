<?php
/**
 * ZanPHP
 *
 * An open source agile and rapid development framework for PHP 5
 *
 * @package		ZanPHP
 * @author		MilkZoft Developer Team
 * @copyright	Copyright (c) 2011, MilkZoft, Inc.
 * @license		http://www.zanphp.com/documentation/en/license/
 * @link		http://www.zanphp.com
 * @version		1.0
 */
 
/**
 * Access from index.php
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

/**
 * ZanPHP Email Class
 *
 * This class allows to manipulate emails
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	classes
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/classes/email_class
 */
class ZP_Data extends ZP_Load {
  	
  	public function __construct() {
  		$this->Db = $this->core("Db");

		$this->ignore = array(
			"save", 
			"edit",
			"_table", 
			"_hide", 
			"_options", 
			"iDir", 
			"iPx", 
			"iApplication", 
			"iPath", 
			"iDirname", 
			"dDir", 
			"dPx", 
			"dApplication", 
			"dPath", 
			"dDirname", 
			"dDirbase", 
			"iDirbase",
			"ID"
		);

		$this->rename = TRUE;
  	}

  	public function ignore($field = FALSE) {
  		if(is_array($field)) {
  			for($i = 0; $i <= count($field) - 1; $i++) {
  				$this->ignore[] = $field[$i];
  			}
  		} elseif(is_string($field)) {
			$this->ignore[] = $field;	
		}
  	}

	public function proccess($data = NULL, $validations = FALSE) {
		if(is_array($validations)) { 
			foreach($validations as $field => $validation) { 
				if($validation === "required") { 
					if(!POST($field)) {
						$field = $this->rename($field);

						return array("error" => getAlert("$field is required"));
					}
				} elseif($validation === "name?") {
					if(!isName(POST($field))) {
						return array("error" => getAlert("$field is not a valid name"));
					}
				} elseif($validation === "email?") {
					if(!isEmail(POST($field))) {
						return array("error" => getAlert("$field is not a valid email"));
					}
				} elseif($validation === "injection?") {
					if(isInjection(POST($field))) {
						return array("error" => getAlert("SQL/HTML injection attempt blocked"));
					}
				} elseif($validation === "spam?") {
					if(isSPAM(POST($field))) {
						return array("error" => getAlert("SPAM prohibited"));
					}
				} elseif($validation === "vulgar?") {
					if(isVulgar(POST($field))) {
						return array("error" => getAlert("Your $field is very vulgar"));
					}
				} elseif($validation === "ping") {
					if(!ping(POST($field))) {
						return array("error" => getAlert("Invalid URL"));
					}
				} elseif(is_string($validation) and substr($validation, 0, 6) === "length") {
					$count = (int) substr($validation, 7, 8);

					$count = ($count > 0) ? $count : 6;

					if(strlen(POST($field)) < $count) {
						return array("error" => getAlert("$field must have at least $count characters"));
					}
				} elseif(isset($field["exists"]) and isset($this->table)) {
					if(is_array($validation)) {
						if(isset($validation["or"]) and count($validation) > 2) {
							unset($validation["or"]);

							$fields = array_keys($validation);	
							
							for($i = 0; $i <= count($fields) - 1; $i++) {
								$exists = $this->Db->findBy($fields[$i], $validation[$fields[$i]]);
			
								if($exists) {
									return array("error" => getAlert("The ". strtolower($fields[$i]) ." already exists"));
								}			
							}
						} else {
							$field = array_keys($validation);

							$exists = $this->Db->findBy($field[0], $validation[$field[0]]);

							if($exists) {
								return array("error" => getAlert("The ". strtolower($field[0]) ." already exists"));
							}	
						}
					}
				}
			}
		}

		if(is_null($data)) {
			$data = array();
		}

		$POST = POST(TRUE);

		foreach($POST as $field => $value) {
			if(!in_array($field, $this->ignore)) { 
				if(!isset($data[$this->rename($field)])) {
					$data[$this->rename($field)] = decode(filter($value, "escape"));	
				}
			}
		}
		
		return $data;
	}

	public function change($field, $newField) {
		$this->changes[$field] = $newField;
	}

	public function rename($field) {
		if($this->rename) {
			if(isset($this->changes[$field])) {
				$field = $this->changes[$field];
			}
			
			$field = str_replace("_", " ", $field);
			$field = ucwords($field);
			$field = str_replace(" ", "_", $field);	
		}

		return $field;
	} 

  	public function table($table) {
  		$this->table = $table;	

  		$this->Db->table($this->table);
  	}

}
