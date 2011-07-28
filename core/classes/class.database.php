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
	
	public $fetch = "assoc";
	
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
			$this->library("AdoDB");
			 
			if(_dbController === "odbc_mssql") {
				$this->Database = ADONewConnection("odbc_mssql");
				
				self::$connection = $this->Database->connect("Driver={SQL Server}; Server=". _dbHost ."; Database=". _dbName .";", _dbUser, _dbPwd);
			} elseif(_dbController === "mysql") {
				$this->Database = ADONewConnection("mysql");
				
				self::$connection = $this->Database->connect(_dbHost, _dbUser, _dbPwd, _dbName);	
			} elseif(_dbController === "mysqli") {
				$this->Database = ADONewConnection("mysqli");
				
				self::$connection = $this->Database->connect(_dbHost, _dbUser, _dbPwd, _dbName);	
			} elseif(_dbController === "postgres") {
				$this->Database = ADONewConnection("postgres");
				
				self::$connection = $this->Database->connect(_dbHost, _dbUser, _dbPwd, _dbName);	
			} elseif(_dbController === "oci8") {
				$this->Database = ADONewConnection("oci8");
				
				self::$connection = $this->Database->connect(_dbHost, _dbUser, _dbPwd, _dbName);			
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
		if($this->fetch === "assoc") {
			$this->Database->setFetchMode(ADODB_FETCH_ASSOC);
		} elseif($this->fetch === "array") {
			$this->Database->setFetchMode(ADODB_FETCH_NUM); 	
		}
		
		if(isset($query)) {
			$this->Rs = $this->Database->_query($query);
		}
		
		return ($this->Rs) ? $this->Rs : FALSE;
	}
	
    /**
     * Performs a SQL insert
     *
     * @param string $table
     * @param string $fields
     * @param string $values
     * @return object or boolean value
     */		
	public function insert($table, $fields = FALSE, $values = FALSE) {
		if(!$table or !$fields) {
			return FALSE;
		}
		
		$data = $fields;
			
		if(is_array($data)) {
			$total   = count($data);
			$_fields = NULL;
			$_values = NULL;
			$i 		 = 0;
			
			foreach($data as $field => $value) {
				if($i === $total) {
					$_fields .= "$field";
					$_values .= "'$value'";
				} else {
					$_fields .= "$field, ";
					$_values .= "'$value', ";	
				}
						
				$i++;	
			}
			
			$query = "INSERT INTO $table ($_fields) VALUES ($_values)";
		} else {
			if(!$values) {
				return FALSE;	
			}
			
			$query = "INSERT INTO $table ($fields) VALUES ($values)";
		}	
		
		return ($this->Database->_query($query)) ? TRUE : FALSE;
	}
	
	public function insertBatch($table, $data) {			
		if(!$table or !$data) {
			return FALSE;
		}
		
		if(isset($data[0])) {
			$inserts = count($data);
			$_fields = NULL;
			$_values = NULL;
			$query   = NULL;
			$i 		 = 0;
			$j 		 = 0;
			
			foreach($data as $insert) {
				$total = count($data[$i]);
				
				foreach($insert as $field => $value) {
					if($i === $total) {
						$_fields .= "$field";
						$_values .= "'$value'";
					} else {
						$_fields .= "$field, ";
						$_values .= "'$value', ";	
					}
							
					$j++;	
					
					$query .= "INSERT INTO $table ($_fields) VALUES ($_values);";
				}
				
				$_fields = NULL;
				$_values = NULL;
				
				$i++;
			}
		} else {
			return FALSE;
		}
		
		return ($this->Database->_query($query)) ? TRUE : FALSE;
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
		if(!$table or !$ID or !$primaryKey) {
			return FALSE;		
		}
		
		$query = "DELETE FROM $table WHERE $primaryKey = $ID";
		
		return ($this->Database->_query($this->Query)) ? TRUE : FALSE;
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
	public function deleteBy($table, $field, $value, $limit = "1") {
		if(!$table or !$field or !$value) {
			return FALSE;
		}
		
		if(_dbController === "odbc_mssql") {
			$query = "DELETE TOP ($limit) FROM $table WHERE $field = $value";
		} else {
			$query = "DELETE FROM $table WHERE $field = $value $limit";
		}
		
		return ($this->Database->_query($query)) ? TRUE : FALSE;
	}
		
    /**
     * Make a deletion by a SQL query
     *
     * @param string $table
     * @param string $SQL
     * @return boolean value
     */
	public function deleteBySQL($table, $SQL) {
		if(!$table or !$SQL) {
			return FALSE;
		}
		
		$query = "DELETE FROM $table WHERE $SQL";
		
		return ($this->Database->_query($query)) ? TRUE : FALSE;
	}	
	
    /**
     * Make an update query by Primary Key
     *
     * @param string $table
     * @param string $values
     * @param integer $ID
     * @return boolean value
     */
	public function update($table, $data, $ID, $primaryKey) {		
		if(!$table or !$values or !$ID or !$primaryKey) {
			return FALSE;
		}
		
		$query = "UPDATE $table SET $values WHERE $primaryKey = $ID";
		
		return ($this->Database->_query($query)) ? TRUE : FALSE;
	}

    /**
     * Make an update by SQL query
     *
     * @param string $table
     * @param string $SQL
     * @return boolean value
     */
	public function updateBySQL($table, $SQL) {
		if(!$table or !$SQL) {
			return FALSE;
		}
		
		$query = "UPDATE $table SET $SQL";
		
		return ($this->Database->_query($query)) ? TRUE : FALSE;
	}	
	
    /**
     * Gets the results into an array
     *
     * @return boolean value / array value
     */
	public function fetch($count = 0) {
		if($this->fetch === "array") {
			return (!$this->Rs) ? FALSE : $this->Rs->fetchRow();
		} elseif($this->fetch === "assoc") {	
			return (!$this->Rs) ? FALSE : $this->Rs->getArray($count);
		} elseif($this->fetch === "object") {
			return (!$this->Rs) ? FALSE : $this->Rs->fetchObject();
		}
	}
	
    /**
     * Gets the count of rows
     *
     * @return boolean value / integer value
     */	
	public function rows() {
		return (!$this->Rs) ? FALSE : $this->Rs->recordCount();	
	}

    /**
     * Gets the last inserted ID
     *
     * @return boolean value / integer value
     */
	public function insertID($table = NULL, $primaryKey = NULL) {
		if(isset($table)) {
			$query = "SELECT TOP 1 $primaryKey FROM $table ORDER BY $primaryKey DESC";
			 	
			$this->Rs = $this->Database->_query($query);
			
			$data = $this->Rs->getArray(1);
			
			return $data[0]["$primaryKey"];
		} else {
			return (self::$connection) ? $this->Database->insert_ID() : FALSE;
		}
	}
		
    /**
     * Frees memory
     *
     * @return boolean value / void
     */
	public function free() {
	 	return ($this->Rs) ? $this->Rs->free() : FALSE;
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
