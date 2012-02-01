<?php
/**
 * ZanPHP
 *
 * An open source agile and rapid development framework for PHP 5
 *
 * @package		ZanPHP
 * @author		MilkZoft Developer Team
 * @copyright	Copyright (c) 2011, MilkZoft, Inc.
 * @license		http://www.zanphp.com/documentation/en/basic/licence
 * @link		http://www.zanphp.com
 */
 
/**
 * Access from index.php
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

/**
 * ZanPHP Load Class
 *
 * This class is used to load models, views, controllers, classes, libraries, helpers as well as interact directly with templates
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	classes
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/classes/load_class
 */
class ZP_Scaffold extends ZP_Load {

	public $action = NULL;
	public $hide   = array();
	public $hideString = NULL;
	public $error = FALSE;
	public $success = FALSE;

	public function __construct() {
		$this->Db = $this->core("Db");
		
		$helpers = array("alerts", "forms", "html");
		$this->helper($helpers);
	}

	public function build() {
		$HTML = NULL;

		$hide   = FALSE;
		$option = FALSE;

		foreach($this->columns as $column) {				
			if(!in_array($column["Field"], $this->hide)) {	
				if(count($this->hide) > 0) {
					if($this->hideString and !$hide) {
						$attributes = array(
							"type"  => "hidden",
							"name"  => "_hide",
							"value" => base64_encode($this->hideString)
						);

						$HTML .= formInput($attributes);

						unset($attributes);	

						$hide = TRUE;
					}
				}
				
				if($this->type($column["Type"]) === "string") {
					$attributes = array(
						"type"  => "text",
						"name"  => $column["Field"],
						"class" => "input",
						"field"	=> $this->rename($column["Field"]),
						"p"		=> TRUE,
						"value" => (!$this->success) ? recoverPOST($column["Field"]) : NULL
					);

					if(isset($this->options)) { 
						if(!$option) {
							$HTML .= formInput(array("type" => "hidden", "name"  => "_options", "value" => base64_encode(serialize($this->options))));
			
							$option = TRUE;
						}

						if(isset($this->options[$column["Field"]])) { 
							if($this->options[$column["Field"]]["type"] === "select") {
								$attrs = array(
									"name"  => $column["Field"], 
									"class" => "select",
									"field"	=> $this->rename($column["Field"]),
									"p"		=> TRUE
								);
								
								$HTML .= formSelect($attrs, $this->options[$column["Field"]]["options"]);

								unset($attrs);
							} elseif($this->options[$column["Field"]]["type"] === "radio") {
								$attrs = array(
									"name"  => $column["Field"],
									"class" => "radio",
									"field"	=> $this->rename($column["Field"]),
									"p"		=> TRUE
								);
								
								$HTML .= formRadio($attrs, $this->options[$column["Field"]]["options"]);

								unset($attrs);
							} elseif($this->options[$column["Field"]]["type"] === "password") {
								$attrs = array(
									"name"  => $column["Field"],
									"type" 	=> "password",
									"class" => "input",
									"field"	=> $this->rename($column["Field"]),
									"p"		=> TRUE
								);

								$HTML .= formInput($attrs);

								unset($attrs);	
							} elseif($this->options[$column["Field"]]["type"] === "hidden") {
								$attrs = array(
									"name"  => $column["Field"],
									"type" 	=> "hidden",
									"value" => isset($this->options[$column["Field"]]["value"]) ? $this->options[$column["Field"]]["value"] : NULL,
								);

								$HTML .= formInput($attrs);

								unset($attrs);	
							}
						} else {
							$HTML .= formInput($attributes);
						}
					} else {
						$HTML .= formInput($attributes);
					}
					
					unset($attributes);
				} elseif($this->type($column["Type"]) === "text") {
					$attributes = array(
						"name"  => $column["Field"],
						"class" => "editor textarea",
						"field"	=> $this->rename($column["Field"]),
						"p"		=> TRUE,
						"value" => (!$this->success) ? recoverPOST($column["Field"]) : NULL
					);
					
					$HTML .= formTextarea($attributes); 
				}	
			}
		}
			
		$HTML .= p(TRUE, "center");

			$attributes = array(
				"type"  => "submit",
				"name"  => "save",
				"value" => __("Save"),
				"class" => "submit",
			);
				
			$HTML .= formInput($attributes);

			unset($attributes);

			$attributes = array(
				"type"  => "submit",
				"name"  => "cancel",
				"value" => __("Cancel"),
				"class" => "submit",
			);
				
			$HTML .= formInput($attributes);

			unset($attributes);

		$HTML .= p(FALSE);
			
		$HTML .= formClose();
			

		return $HTML;
		
	}	

	public function error($error) {
		$this->error = $error;
	}
	
	public function hide($hide) {
		if(is_string($hide)) {
			$hide = str_replace(", ", ",", $hide);

			$parts = explode(",", $hide);

			if(count($parts) > 0) {
				for($i = 0; $i <= count($parts) - 1; $i++) {
					$this->hide[] = $parts[$i];
				}
			} else {
				$this->hide = array();
			}

			$this->hideString = $hide;
		} else {
			if(count($hide) > 0) { 
				for($i = 0; $i <= count($hide) - 1; $i++) {
					if($i === count($hide) - 1) {
						$this->hideString .= $hide[$i];
					} else {
						$this->hideString .= $hide[$i] . ",";	
					}	
				}

				$this->hide = $hide;
			} else {
				$this->hide = array();
			}	
		}
	}
	
	public function make() {
		$this->action = isLang() ? _webBase . "/" . segment(0) . "/" . segment(1) . "/" . segment(2) : _webBase . "/" . segment(0) . "/" . segment(1);
		
		if(is_array($this->columns)) {
			$this->CSS("scaffold");

			$this->js("tiny-mce", NULL, "class");
			
			$HTML = formOpen($this->action, "scaffold");

			if($this->error) {
				$HTML .= $this->error;
			} elseif($this->success) {
				$HTML .= $this->success;
			}
			
			$attributes = array(
				"type"  => "hidden",
				"name"  => "_table",
				"value" => base64_encode($this->table)
			);

			$HTML .= formInput($attributes);

			unset($attributes);
			
		}

		$HTML .= $this->build();

		$vars["HTML"] = $HTML;
		
		$this->template("scaffold", $vars);	
		
		$this->render("header", "footer");
	}

	public function options($options) {
		$this->options = $options;
	}

	public function rename($field) {
		$field = str_replace("_", " ", $field);

		return __(ucfirst($field));
	}

	public function save() {
		if(POST("save")) {
			$this->values(POST(TRUE));

			$error = $this->validate();

			if($error) {
				$table   = POST("_table", "b64");
				$hide    = POST("_hide", "b64");
				$options = POST("_options", "unserialize");
				
				$this->table($table);
				$this->hide($hide);
				$this->options($options);
				$this->error($error);
				$this->make();
			} else {
				die("Si");
			}
		}
	}

	public function table($table) {
		$this->table = $table;

		$this->columns = $this->Db->columns($table);
	}
	
	public function type($type) {
		if(stristr($type, "mediumint")) {
			return "integer";
		} elseif(stristr($type, "int")) {
			return "integer";
		} elseif(stristr($type, "varchar")) {
			return "string";
		} elseif(stristr($type, "text")) {
			return "text";
		}
	}

	public function validate() {
		if(is_array($this->validations)) {
			foreach($this->validations as $field => $validation) {
				if($validation === "required") {
					if(!POST($field)) {
						return getAlert("$field is required");
					}
				}
			}
		}

		return FALSE;
	}

	public function validations($validations) {
		$this->validations = $validations;
	}

	public function values($values) {
		$this->values = $values;
	}
	
}
