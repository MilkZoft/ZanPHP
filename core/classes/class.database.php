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
 * ZanPHP Database Class
 *
 * This class selects the driver for the database to use and sends to call their respective methods
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	classes
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/classes/database_class
 */
class ZP_Database extends ZP_Load {
	
	/**
	 * Contains the count of records
	 * 
	 * @var private $count = 0
	 */
	private $counts = 0;
	
	/**
	 * Contains the SQL query
	 * 
	 * @var private $SQL
	 */
	private $SQL;	
	
	/**
	 * Contains the object of the database driver
	 * 
	 * @var public $Database
	 */
	public $Database;
	
	/**
	 * Contains the connection of the database
	 * 
	 * @var private static $connection
	 */
	private static $connection;
	
    /**
     * Make connection
     *
     * @return void
     */
	public function __construct() {
		$this->config("database");
		$this->connect();		
	}
	
    /**
     * Select database driver and make connection
     *
     * @return void
     */
	public function connect() {
		if(self::$connection != TRUE) {
			if(_dbController === "MsSQL") {
				$this->Database   = $this->core("MsSQL_Db");
				
				self::$connection = $this->Database->connect();
			} elseif(_dbController === "MySQL") {
				$this->Database   = $this->core("MySQL_Db");
				
				self::$connection = $this->Database->connect();			
			} elseif(_dbController === "MySQLi") {
				$this->Database   = $this->core("MySQLi_Db");
				
				self::$connection = $this->Database->connect();
			} elseif(_dbController === "PgSQL") {
				$this->Database   = $this->core("PgSQL_Db");
				
				self::$connection = $this->Database->connect();
			}
		}									
	}
		
    /**
     * Exec free SQL query
     *
     * @param string $query
     * @return mixed (object or boolean) value
     */
	public function query($query) {							
		if(isset($query)) {
			$this->rs = $this->Database->query($query);
		}
		
		return ($this->rs) ? $this->rs : FALSE;
	}
    /**
     * Performs a SQL insert
     *
     * @param string $table
     * @param string $fields
     * @param string $values
     * @return object or boolean value
     */		
	public function insert($table, $fields, $values) {			
		return ($this->Database->insert($table, $fields, $values)) ? TRUE : FALSE;
	}

    /**
     * Make a delete query by Primary Key
     *
     * @param string $table
     * @param integer $ID
	 * @param string $primaryKey
     * @return boolean value
     */
	public function delete($table, $ID, $primaryKey) {		
		return ($this->Database->delete($table, $ID, $primaryKey)) ? TRUE : FALSE;	
	}
	
    /**
     * Make a delete query by specific field and value
     *
     * @param string $table
     * @param string $field
     * @param string $value
     * @param string $limit = "LIMIT 1"
     * @return boolean value
     */
	public function deleteBy($table, $field, $value, $limit = "LIMIT 1") {					
		return ($this->Database->deleteBy($table, $field, $value, $limit)) ? TRUE : FALSE;
	}
		
    /**
     * Make a deletion by a SQL query
     *
     * @param string $table
     * @param string $SQL
     * @return boolean value
     */
	public function deleteBySQL($table, $SQL) {
		return ($this->Database->deleteBySQL($table, $SQL)) ? TRUE : FALSE;
	}	
	
    /**
     * Make an update query by Primary Key
     *
     * @param string $table
     * @param string $values
     * @param integer $ID
     * @return boolean value
     */
	public function update($table, $values, $ID, $primaryKey) {		
		return ($this->Database->update($table, $values, $ID, $primaryKey)) ? TRUE : FALSE;
	}

    /**
     * Make an update by SQL query
     *
     * @param string $table
     * @param string $SQL
     * @return boolean value
     */
	public function updateBySQL($table, $SQL) {
		return ($this->Database->updateBySQL($table, $SQL)) ? TRUE : FALSE;
	}	
	
    /**
     * Gets the results into an array
     *
     * @return boolean value / array value
     */
	public function fetch() {			
		return (!$this->rs) ? FALSE : $this->Database->fetch($this->rs);	
	}
	
    /**
     * Gets the count of rows
     *
     * @return boolean value / integer value
     */	
	public function rows() {
		return (!$this->rs) ? FALSE : $this->Database->rows($this->rs);	
	}

    /**
     * Gets the last inserted ID
     *
     * @return boolean value / integer value
     */
	public function insertID() {
		return (!self::$connection) ? FALSE : $this->Database->insertID(); 
	}
		
    /**
     * Frees memory
     *
     * @return boolean value / void
     */
	public function free() {
	 	return (!$this->rs) ? FALSE : $this->Database->free();
	}
	
    /**
     * Closes the current database connection
     *
     * @return boolean value / void
     */
	public function close() {
		return (!self::$connection) ? FALSE : $this->Database->close(self::$connection);
	}

	/**
     * Begins a transaction
     *
     * @return void
     */
	public function begin() {
		return $this->Database->begin();
	}

	/**
     * Saves changes
     *
     * @return void
     */	
	public function commit() {
		return $this->Database->commit();
	}

	/**
     * Ignore changes
     *
     * @return void
     */		
	public function rollBack() {
		return $this->Database->rollBack();
	}	
}
