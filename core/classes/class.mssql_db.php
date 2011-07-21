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
 * ZanPHP MsSQL_Db Class
 *
 * This class is responsible for interacting directly with the database
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	classes
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/classes/mssql_db_class
 */
class ZP_MsSQL_Db extends ZP_Load {

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
			self::$connection = mssql_connect(_dbHost, _dbUser, _dbPwd, _dbName);
		}
		
		return self::$connection;
	}
	
	/**
     * Begin transaction and set to false the autocommit
     *
     * @return boolean value
     */
	public function begin() {
		mssql_query("BEGIN");
		mssql_autocommit(FALSE);
		
		return TRUE;
	}
	
	/**
     * Saves changes
     *
     * @return void
     */	
	public function commit() {
		return mssql_query("COMMIT");
	}
	
	/**
     * Ignore changes
     *
     * @return void
     */	
	public function rollBack() {
		return mssql_query("ROLLBACK");
	}
	
	/**
     * Execute query
     *
     * @return object value
     */	
	public function query($SQL) {
		if($SQL != "") {
			$this->query = mssql_query($SQL);			
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

		return (mssql_query($query)) ? TRUE : FALSE;
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
		
		return (mssql_query($query)) ? TRUE : FALSE;	
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
		
		return (mssql_query($query)) ? TRUE : FALSE;
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
		
		return (mssql_query($query)) ? TRUE : FALSE;
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
		
		return (mssql_query($query)) ? TRUE : FALSE;
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
		
		return (mssql_query($query)) ? TRUE : FALSE;
	}	
	
	/**
     * Get an array from a SQL query
     *
     * @return array value
     */	
	public function fetch() {			
		return (!$this->query) ? FALSE : mssql_fetch_assoc($this->query);	
	}

	/**
     * Get the number of rows found
     *
     * @return integer value
     */
	public function rows() {
		return (!$this->query) ? FALSE : (int) mssql_num_rows($this->query);	
	}

	/**
     * Get the last inserted ID
     *
     * @return integer value
     */
	public function insertID() {
		return mssql_insert_id(self::$connection);
	}

	/**
     * Frees memory
     *
     * @return void
     */
	public function free() {
	 	return (!$this->query) ? FALSE : mssql_free_result($this->query);
	}
	
	/**
     * Closes connection
     *
     * @return void
     */
	public function close() {
		return (!self::$connection) ? FALSE : mssql_close(self::$connection); 	
	}	
}