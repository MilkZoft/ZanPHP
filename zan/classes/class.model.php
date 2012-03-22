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
class ZP_Model extends ZP_Load {
	
	/**
	 * 
	 * 
	 * 
	 */
	public $Db;
		
	/**
	 * 
	 * 
	 * 
	 */
	public function db($type = "db") {
		if(strtolower($type) === "db") {
			$this->Db = $this->core("Db");
		} elseif(strtolower($type) === "mongodb" or strtolower($type) === "mongo") {
			$this->Db = $this->core("MongoDB");
		} elseif(strtolower($type) === "couchdb" or strtolower($type) === "couch") {
			$this->Db = $this->core("CouchDB");
		} elseif(strtolower($type) === "cassandra") {
			$this->Db = $this->core("Cassandra");
		}

		return $this->Db;
	}
	
	/**
	 * 
	 * 
	 * 
	 */
	public function helpers() {
		$helpers = array("array", "alerts", "debugging", "files", "time", "string", "html", "security", "validations");
		
		$this->helper($helpers);
	}
	
}
