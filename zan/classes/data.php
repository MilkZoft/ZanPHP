<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

class ZP_Data extends ZP_Load
{
	public function __construct()
	{
		$this->Db = $this->core("Db");
		$this->ignore = array("save", "edit", "_table", "_hide", "_options", "ID");
		$this->rename = true;
	}

	public function ignore($field = false)
	{
		if (is_array($field)) {
			for ($i = 0; $i <= count($field) - 1; $i++) {
				$this->ignore[] = $field[$i];
			}
		} elseif (is_string($field)) {
			$this->ignore[] = $field;
		}
	}

	public function process($data = null, $validations = false)
	{
		if (is_array($validations)) {
			foreach ($validations as $field => $validation) {
				if ($validation === "required") {
					if (!POST($field)) {
						$field = $this->rename($field);

						return array("error" => getAlert(__("$field is required")));
					}
				} elseif ($validation === "name?") {
					if (!isName(POST($field))) {
						return array("error" => getAlert(__("$field is not a valid name")));
					}
				} elseif ($validation === "email?") {
					if (!isEmail(POST($field))) {
						return array("error" => getAlert(__("$field is not a valid email")));
					}
				} elseif ($validation === "captcha?") {
					if (!POST("captcha_token") or !POST("captcha_type")) {
						return array("error" => getAlert(__(POST("captcha_type") === "aritmethic" ? "Please enter your answer again" : "Please type the characters you see in the picture")));
					} elseif (POST("captcha_type") === "aritmethic") {								
						if (SESSION("ZanCaptcha". POST("captcha_token")) != POST($field)) {
							return array("error" => getAlert(__("Your answer was incorrect")));
						}
					} else {
						if (SESSION("ZanCaptcha". POST("captcha_token")) !== POST($field)) {
							return array("error" => getAlert(__("The characters did not match the picture")));
						}
					}
				} elseif ($validation === "injection?") {
					if (isInjection(POST($field))) {
						return array("error" => getAlert(__("SQL/HTML injection attempt blocked")));
					}
				} elseif ($validation === "spam?") {
					if (isSPAM(POST($field))) {
						return array("error" => getAlert(__("SPAM prohibited")));
					}
				} elseif ($validation === "vulgar?") {
					if (isVulgar(POST($field))) {
						return array("error" => getAlert(__("Your $field is very vulgar")));
					}
				} elseif ($validation === "ping") {
					if (!ping(POST($field))) {
						return array("error" => getAlert(__("Invalid URL")));
					}
				} elseif (is_string($validation) and substr($validation, 0, 6) === "length") {
					$count = (int) substr($validation, 7, 8);
					$count = ($count > 0) ? $count : 6;

					if (strlen(POST($field)) < $count) {
						return array("error" => getAlert( __("$field")." ".__("must have at least")." $count ".__("characters")));
					}
				} elseif (isset($field["exists"]) and isset($this->table)) {
					if (is_array($validation)) {
						if (isset($validation["or"]) and count($validation) > 2) {
							unset($validation["or"]);

							$fields = array_keys($validation);
							
							for ($i = 0; $i <= count($fields) - 1; $i++) {
								$exists = $this->Db->findBy($fields[$i], $validation[$fields[$i]]);
			
								if ($exists) {
									return array("error" => getAlert(__("The ". strtolower($fields[$i]) ." already exists")));
								}			
							}
						} else {
							$field = array_keys($validation);
							$exists = $this->Db->findBy($field[0], $validation[$field[0]]);

							if ($exists) {
								return array("error" => getAlert(__("The ". strtolower($field[0]) ." already exists")));
							}
						}
					}
				}
			}
		}

		if (is_null($data)) {
			$data = array();
		}

		$POST = POST(true);

		foreach ($POST as $field => $value) {
			if (!in_array($field, $this->ignore)) { 
				if (!isset($data[$this->rename($field)])) {
					$data[$this->rename($field)] = decode(filter($value, "escape"));
				}
			}
		}
		
		return $data;
	}

	public function change($field, $newField)
	{
		$this->changes[$field] = $newField;
	}

	public function rename($field)
	{
		if ($this->rename) {
			if (isset($this->changes[$field])) {
				$field = $this->changes[$field];
			}
			
			$field = str_replace("_", " ", $field);
			$field = ucwords($field);
			$field = str_replace(" ", "_", $field);
		}

		return $field;
	}

	public function table($table) 
	{
		$this->table = $table;
		$this->Db->table($this->table);
	}
}