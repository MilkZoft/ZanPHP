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
 * ZanPHP MySQLi_Db Class
 *
 * This class is responsible for interacting directly with the database
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	classes
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/classes/mysqli_db_class
 */
class ZP_PgSQL extends ZP_Load {

	/**
	 * Contains the connection of the database
	 * 
	 * @var private static $connection
	 */
	private static $connection;
	
	/**
	 * Contains the SQL query
	 * 
	 * @var private $SQL
	 */
	private $SQL;
	
    /**
     * Make connection
     *
     * @return object value
     */
	public function connect() {
		if(self::$connection === NULL) {
			$dsn = "host=". _dbHost ." port=". _dbPort ." dbname=". _dbName ." user=". _dbUser ." password=". _dbPwd ."";
			
			self::$connection = pg_connect($dsn);
		}
		
		return self::$connection;
	}
	
	/**
     * Execute query
     *
     * @return object value
     */	
	public function query($SQL) {
		if($SQL !== "") {
			$this->query = pg_query(self::$connection, $SQL);
		}

		return ($this->query) ? $this->query : FALSE;
	}

	/**
     * Insert a row
     *
     * @return boolean value
     */	
	public function insert($table, $fields, $values) {
		if(!$table or !$fields or !$values) {
			return FALSE;
		}
		
		$query = "INSERT INTO $table ($fields) VALUES ($values)";
		
		return ($this->query($query)) ? TRUE : FALSE;
	}

	/**
     * Delete a row by primary key
     *
     * @return boolean value
     */	
	public function delete($table, $ID, $primaryKey) {
		if(!$table or !$ID or !$primaryKey) {
			return FALSE;		
		}
		
		$query = "DELETE FROM $table WHERE $primaryKey = $ID";
		
		return ($this->query($query)) ? TRUE : FALSE;	
	}
	
	/**
     * Delete rows by specific field and value
     *
     * @return boolean value
     */	
	public function deleteBy($table, $field, $value, $limit = "LIMIT 1") {	
		if(!$table or !$field or !$value) {
			return FALSE;
		}
		
		if($limit > 1) {
			$limit = "LIMIT $limit";
		}
		
		$query = "DELETE FROM $table WHERE $field = '$value' $limit";
		
		return ($this->query($query)) ? TRUE : FALSE;
	}
		
	/**
     * Delete rows by SQL query
     *
     * @return boolean value
     */	
	public function deleteBySQL($table, $SQL) {
		if(!$table or !$SQL) {
			return FALSE;
		}
		
		$query = "DELETE FROM $table WHERE $SQL";
		
		return ($this->query($query)) ? TRUE : FALSE;
	}	
	
	/**
     * Update a row by primary key
     *
     * @return boolean value
     */	
	public function update($table, $values, $ID, $primaryKey) {
		if(!$table or !$values or !$ID or !$primaryKey) {
			return FALSE;
		}
		
		$query = "UPDATE $table SET $values WHERE $primaryKey = $ID";
		
		return ($this->query($query)) ? TRUE : FALSE;
	}

	/**
     * Update rows by SQL query
     *
     * @return boolean value
     */	
	public function updateBySQL($table, $values) {
		if(!$table or !$values) {
			return FALSE;		
		}
		
		$query = "UPDATE $table SET $values"; 
		
		return ($this->query($query)) ? TRUE : FALSE;
	}	
	
	/**
     * Get an array from a SQL query
     *
     * @return array value
     */	
	public function fetch($type) {			
		return (!$this->query) ? FALSE : pg_fetch_assoc($this->query);	
	}

	/**
     * Get the number of rows found
     *
     * @return integer value
     */
	public function rows() {
		return (!$this->query) ? FALSE : (int) pg_num_rows($this->query);	
	}

	/**
     * Get the last inserted ID
     *
     * @return integer value
     */
	public function insertID() {
		return (!$this->query) ? FALSE : (int) pg_last_oid($this->query);
	}

	/**
     * Frees memory
     *
     * @return void
     */
	public function free() {
	 	return (!$this->query) ? FALSE : pg_free_result($this->query);
	}
	
	/**
     * Closes connection
     *
     * @return void
     */
	public function close() {
		return (!self::$connection) ? FALSE : pg_close(self::$connection); 	
	}	
	
}
