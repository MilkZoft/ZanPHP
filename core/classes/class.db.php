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
 * ZanPHP Db Class
 *
 * This class facilitates the creation of queries to the database
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	classes
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/classes/db_class
 */
class ZP_Db extends ZP_Load {
	
	/**
	 * Contains the row content in fetch mode
	 * 
	 * @var private $row
	 */
	private $row;
	
	/**
	 * Contains the primary key field
	 * 
	 * @var private $primaryKey = FALSE
	 */
	private $primaryKey = FALSE;
	
	/**
	 * Insert count for transactions
	 * 
	 * @var private $inserts = 0
	 */
	private $inserts = 0;
	
	/**
	 * Contains the query string
	 * 
	 * @var private $query
	 */
	private $query;
	
	/**
	 * Contains the name of the table
	 * 
	 * @var private $table
	 */
	private $table;
	
	/**
	 * Contains the fields of the table
	 * 
	 * @var private $fields
	 */
	private $fields;
	
	/**
	 * Contains the values of the query
	 * 
	 * @var private $values
	 */
	private $values;
	
	/**
	 * A flag to determinate encoding
	 * 
	 * @var private $encode
	 */
	private $encode = FALSE;
	
	private $select = "SELECT *";
	
	private $join = NULL;
	
	private $where = NULL;
		
    /**
     * Load Database class
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
				
				self::$connection = $this->Database->connect("Driver={SQL Server}; Server=". _dbHost .", ". _dbPort ."; Database=". _dbName .";", _dbUser, _dbPwd);
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
	
	public function encode($encode = FALSE) {
		$this->encode = $encode;
	}
	
	public function fetchMode($fetch = "assoc") {
		$this->fetchMode = $fetch;	
	}
	
    /**
     * Set table and fields to make a SQL query
     *
     * @param string $table
     * @param string $fields = "*"
     * @return void
     */
	public function table($table, $fields = "*") {
		$table = str_replace(_dbPfx, "", $table);
		
		$this->table  = _dbPfx . $table;  
		$this->fields = $fields;

		$data = $this->query("SHOW COLUMNS FROM $this->table");
		
		if(is_array($data)) {
			foreach($data as $column) {
				if($column["Key"] === "PRI") {
					$this->primaryKey = $column["Field"];
					
					return $this->primaryKey;
				}
			}
		}
		
		return NULL;
	}

    /**
     * Set values for make a insert or update query
     *
     * @param string $values
     * @return void
     */
	public function values($values) {
		$this->values = $values;	
	}
	
	public function select($fields = "*", $normal = TRUE) {
		if(!$normal) {
			$this->select = $fields;	
		} else {
			$this->select = "SELECT $fields";	
		}
	}
	
	public function selectMax($field, $as = FALSE) {
		if(isset($field) and $as) {
			$this->select = "SELECT MAX($field) as $as";	
		} else {
			$this->select = "SELECT MAX($field) as $field";
		}
	}
	
	public function selectMin($min, $as = FALSE) {
		if(isset($min) and $as) {
			$this->select = "SELECT MIN($field) as $as";	
		} else {
			$this->select = "SELECT MIN($field) as $field";
		}	
	}
	
	public function selectAvg($field, $as = FALSE) {
		if(isset($field) and $as) {
			$this->select = "SELECT AVG($field) as $as";	
		} else {
			$this->select = "SELECT AVG($field) as $field";
		}	
	}
	
	public function selectSum($field, $as = FALSE) {
		if(isset($field) and $as) {
			$this->select = "SELECT SUM($field) as $as";	
		} else {
			$this->select = "SELECT SUM($field) as $field";
		}	
	}
	
	public function from($table) {
		$this->from = $table;	
	}
	
	public function join($table, $condition, $position = FALSE) {
		if(!$table or !$condition) {
			return FALSE;	
		}
		
		if(!$position) {
			$this->join = "JOIN $table ON $condition";
		} else {
			$this->join = "$position JOIN $table ON $condition";	
		}
	}
	
	public function set($field, $value) {
		$this->data[$field] = $value;
	}
	
	public function get($table = FALSE, $limit = 0, $offset = 0) {
		if($limit === 0 and $offset === 0) {
			if($table) {
				$query = "$this->select FROM $table $this->join $this->where";
			} else {
				$query = "$this->select FROM $this->from $this->join $this->where";
			}
		} else {
			if($table) {
				$query = "$this->select FROM $table $this->join $this->where LIMIT $limit, $offset";
			} else {
				$query = "$this->select FROM $this->from $this->join $this->where LIMIT $limit, $offset";	
			}
		}
		
		$rs = $this->Database->query($query);	
		
		if($this->Database->rows() === 0) {
			return FALSE;
		} elseif($this->Database->rows() === 1) {
			$rows[] = $this->Database->fetch();			
		} else {
			while($row = $this->Database->fetch()) {
				$rows[] = $row;	
			}
		}		
		
		$this->Database->free();
		
		if($this->encode) {
			if($this->fetch === "assoc") {
				return isset($rows) ? $this->encoding($rows) : FALSE;
			} else {
				return isset($rows) ? $rows : FALSE;	
			}
		} else {
			return isset($rows) ? $rows : FALSE;
		}
	}
	
	public function getWhere($table, $where, $limit = 0, $offset = 0) {
		$i = 0;
		$count = count($where);
		
		foreach($where as $field => $value) {
			if($i === $count) {
				$_where = "$field = '$value'";
			} else {
				$_where = "$field = '$value' AND ";
			}
			
			$i++;
		}
		
		if($limit === 0 and $offset === 0) {
			$query = "$this->select FROM $table WHERE $_where"; 
		} else {
			$query = "SELECT $this->fields FROM $table WHERE $_where LIMIT $limit, $offset";	
		}
	}
	
	public function where($data, $value = FALSE) {
		if(is_array($data)) {
			$total = count($data);
			$i = 0;
			$this->where = NULL;
			$_where = NULL;
			
			foreach($data as $field => $value) {
				$parts = explode(" ", $field);
				
				if($i === $total) {
					if(count($parts) === 2) {
						$_where .= "$parts[0] $parts[1] '$value'";
					} else {
						$_where .= "$field = '$value'";
					}
				} else {
					if(count($parts) === 2) {
						$_where .= "$parts[0] $parts[1] '$value' AND ";
					} else {
						$_where .= "$field = '$value' AND ";
					}
				}
				
				unset($parts);
				
				$i++;
			}
			
			if(is_null($this->where)) {
				$this->where = "WHERE $_where";
			} else {
				$this->where .= " AND $_where";
			}
		} else {
			if(isset($data) and !$value) {
				$this->where = "WHERE $data";	
			} else {
				if(is_null($this->where)) {
					$parts = explode(" ", $data);
					
					if(count($parts) === 2) {
						$this->where = "WHERE parts[0] $parts[1] '$value'";	
					} else {
						$this->where = "WHERE $data = '$value'";
					}
				} else {
					$parts = explode(" ", $data);
					
					if(count($parts) === 2) {
						$this->where .= " AND $parts[0] $parts[1] '$value'";
					} else {
						$this->where .= " AND $data = '$value'";	
					}	
				}	
			}
		}
	}
	
	public function whereOr($data, $value = NULL) {
		if(is_array($data)) {
			$total = count($data);
			$i = 0;
			$this->where = NULL;
			
			foreach($data as $field => $value) {
				if($i === $total) {
					$this->where .= "$field = '$value'";
				} else {
					$this->where .= "$field = '$value' OR ";
				}
				
				$i++;
			}
		} else {
			$this->where = "$data = '$value'";
		}
	}
	
	public function whereIn($field, $data) {
		if(is_array($data)) {
			for($i = 0; $i <= count($data) - 1; $i++) {
				if($i === count($data) - 1) {
					$values .= "'$data[$i]'";	
				} else {
					$values .= "'$data[$i]', ";
				}
			}
			
			$this->where = "$field IN ($values);";
		} else {
			$this->where = "$field IN ('$data')";	
		}
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
		
		$this->Rs = $this->Database->_query($query);
		
		if($this->Rs) {
			if(!$this->primaryKey) {
				return TRUE;
			} else {
				$insertID = $this->Database->insertID();
						
				return $insertID;
			}
		}
		
		return FALSE;
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
     * Make an update query by Primary Key
     *
     * @param string $table
     * @param string $values
     * @param integer $ID
     * @return boolean value
     */
	public function update($ID = FALSE) {		
		if(!$this->table or !$this->values or !$ID or !$this->primaryKey) {
			return FALSE;
		}
		
		$query = "UPDATE $this->table SET $this->values WHERE $this->primaryKey = $ID";
		
		return ($this->Database->_query($query)) ? TRUE : FALSE;
	}
	
    /**
     * Make an update by SQL query
     *
     * @param string $table
     * @param string $SQL
     * @return boolean value
     */
	public function updateBySQL() {
		if(!$this->table or !$this->SQL) {
			return FALSE;
		}
		
		$query = "UPDATE $this->table SET $this->SQL";
		
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
	public function delete($ID = FALSE) {	
		if(!$this->table or !$ID or !$this->primaryKey) {
			return FALSE;		
		}
		
		$query = "DELETE FROM $this->table WHERE $primaryKey = $ID";
		
		return ($this->Database->_query($query)) ? TRUE : FALSE;
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
	public function deleteBy($field = FALSE, $value = FALSE, $limit = "1") {
		if(!$this->table or !$field or !$value) {
			return FALSE;
		}
		
		if(_dbController === "odbc_mssql") {
			$query = "DELETE TOP ($limit) FROM $this->table WHERE $field = $value";
		} else {
			$query = "DELETE FROM $this->table WHERE $field = $value $limit";
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
	public function deleteBySQL($SQL = FALSE) {
		if(!$this->table or !$SQL) {
			return FALSE;
		}
		
		$query = "DELETE FROM $this->table WHERE $SQL";
		
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
	public function insertID($table = FALSE) {
		if($table) {
			$query = "SELECT TOP 1 $this->primaryKey FROM $this->table ORDER BY $this->primaryKey DESC";
			 	
			$this->Rs = $this->Database->_query($query);
			
			$data = $this->Rs->getArray(1);
			
			return $data[0]["$primaryKey"];
		} else {
			return (self::$connection) ? $this->Database->insert_ID() : FALSE;
		}
	}
	
    /**
     * Decide whether the system deletes, updates or inserts
     *
     * @param string $option = NULL
     * @return boolean value
     */
	public function save($option = NULL) {	
		if($option === FALSE) {
			return $this->updateBySQL();
		} elseif($option === NULL) {
			return $this->insert();	
		} elseif($option === "begin") {
			return $this->insert(TRUE);
		} elseif($option > 0) {
			return $this->update($option);	
		}
	}
	
    /**
     * Find record by primary key
     *
     * @param integer $ID
     * @return boolean value / array value
     */
	public function find($ID) {
		$query = "SELECT $this->fields FROM $this->table WHERE $this->primaryKey = $ID"; 
		
		$rs = $this->Database->query($query);	

		if($this->Database->rows() === 0) {
			return FALSE;
		} elseif($this->Database->rows() === 1) {
			$rows[] = $this->Database->fetch();			
		}		
		
		$this->Database->free();
		
		if($this->encode) {
			return isset($rows) ? $this->encoding($rows) : FALSE;
		} else {
			return isset($rows) ? $rows : FALSE;
		}				
	}
		
    /**
     * Find records by specific field and value
     *
     * @param string $field
     * @param string $value
     * @param string $group = NULL
     * @param string $order = NULL
     * @param string $limit = NULL
     * @return array value
     */
	public function findBy($field, $value, $group = NULL, $order = NULL, $limit = NULL) {
		$SQL = NULL;
		
		if($group !== NULL) {
			$SQL .= " GROUP BY " . $group;
		}
		
		if($order === FALSE) {
			$SQL .= "";
		} elseif($order !== NULL) {
			$SQL .= " ORDER BY " . $order;
		} elseif($order === "") {
			$SQL .= " ORDER BY $this->primaryKey";
		}
		
		if($limit !== NULL) {
			$SQL .= " LIMIT ".$limit;
		}
		
		$query = "SELECT $this->fields FROM $this->table WHERE $field = '$value'$SQL"; 

		$rs = $this->Database->query($query);

		if($this->Database->rows() === 0) {
			return FALSE;
		} elseif($this->Database->rows() === 1) {
			$rows[] = $this->Database->fetch();			
		} else {
			while($row = $this->Database->fetch()) {
				$rows[] = $row;	
			}
		}
		
		$this->Database->free();
		
		if($this->encode) {
			return isset($rows) ? $this->encoding($rows) : FALSE;
		} else {
			return isset($rows) ? $rows : FALSE;
		}
	}
	
    /**
     * Find records by SQL query
     *
     * @param string $SQL 
     * @param string $group = NULL
     * @param string $order = NULL
     * @param string $limit = NULL
     * @return array value
     */
	public function findBySQL($SQL, $group = NULL, $order = NULL, $limit = NULL) {					
		if($group !== NULL) {
			$SQL .= " GROUP BY ".$group;
		}
		
		if($order === FALSE) {
			$SQL .= "";		
		} elseif($order !== NULL) {
			$SQL .= " ORDER BY ".$order;
		} elseif($order === "") {
			$SQL .= " ORDER BY $this->primaryKey";
		}
		
		if($limit !== NULL) {
			$SQL .= " LIMIT ".$limit;
		}
		
		$query = "SELECT $this->fields FROM $this->table WHERE $SQL";		
		
		$rs = $this->Database->query($query);
		
		if($this->Database->rows() === 0) {
			return FALSE;
		} elseif($this->Database->rows() === 1) {
			$rows[] = $this->Database->fetch();			
		} else {
			while($row = $this->Database->fetch()) {
				$rows[] = $row;	
			}
		}

		$this->Database->free();
		
		if($this->encode) {
			return (isset($rows)) ? $this->encoding($rows) : FALSE;
		} else {
			return (isset($rows)) ? $rows : FALSE;
		}
	}
		
    /**
     * Find the last record
     *
     * @return array value
     */
	public function findLast() {		
		$query = "SELECT $this->fields FROM $this->table ORDER BY $this->primaryKey DESC LIMIT 1";
		
		$rs = $this->Database->query($query);
		
		if($this->Database->rows() === 1) {
			$rows[] = $this->Database->fetch();
		} else {
			return FALSE;
		}
		
		$this->Database->free();
		
		if($this->encode) {
			return (isset($rows)) ? $this->encoding($rows) : FALSE;
		} else {
			return (isset($rows)) ? $rows : FALSE;
		}
	}
	
	public function findFirst() {
		$query = "SELECT $this->fields FROM $this->table ORDER BY $this->primaryKey ASC LIMIT 1";
		
		$rs = $this->Database->query($query);
		
		if($this->Database->rows() === 1) {
			$rows[] = $this->Database->fetch();
		} else {
			return FALSE;
		}
		
		$this->Database->free();
		
		if($this->encode) {
			return (isset($rows)) ? $this->encoding($rows) : FALSE;
		} else {
			return (isset($rows)) ? $rows : FALSE;
		}	
	}
	
    /**
     * Find all records
     *
     * @param string $group = NULL
     * @param string $order = NULL
     * @param string $limit = NULL
     * @return array value
     */
	public function findAll($group = NULL, $order = NULL, $limit = NULL) {
		$SQL = NULL;
		
		if($group !== NULL) {
			$SQL .= " GROUP BY ".$group;
		}
		
		if($order === FALSE) {
			$SQL .= "";		
		} elseif($order !== NULL) {
			$SQL .= " ORDER BY ".$order;
		} elseif($order === NULL) {
			$SQL .= " ORDER BY $this->primaryKey";
		}
		
		if($limit !== NULL) {
			$SQL .= " LIMIT ".$limit;
		}
		
		$query = "SELECT $this->fields FROM $this->table$SQL";

		$rs = $this->Database->query($query);						
		
		if($this->Database->rows() === 0) {
			return FALSE;
		} elseif($this->Database->rows() === 1) {
			$rows[] = $this->Database->fetch();
		} else {
			while($row = $this->Database->fetch()) {
				$rows[] = $row;	
			}
		}

		$this->Database->free();
		
		if($this->encode) {
			return (isset($rows)) ? $this->encoding($rows) : FALSE;
		} else {
			return (isset($rows)) ? $rows : FALSE;
		}
	}
	
    /**
     * Count all records
     *
     * @return integer value
     */	
	public function countAll() {		
		$query = "SELECT $this->fields FROM $this->table";
		
		$rs = $this->Database->query($query);
		
		return $this->Database->rows();
	}

    /**
     * Count records by SQL query
     *
     * @return integer value
     */
	public function countBySQL($SQL) {		
		if($SQL	=== "") {
			return FALSE;
		}
		
		$query = "SELECT $this->fields FROM $this->table WHERE $SQL";

		$rs = $this->Database->query($query);
		
		return $this->Database->rows();
	}		
		
    /**
     * Make a free query
     *
     * @return void
     */	
	public function query($query) {		
		$query = $this->Database->query($query);
		
		if($this->Database->rows() === 0) {
			return FALSE;
		} elseif($this->Database->rows() === 1) {
			$rows[] = $this->Database->fetch();			
		} else { 
			while($row = $this->Database->fetch()) {
				$rows[] = $row;	
			}
		}
		
		$this->Database->free();
		
		if($this->encode) {
			return isset($rows) ? $this->encoding($rows) : FALSE;
		} else {
			return isset($rows) ? $rows : FALSE;
		}
	}
	
    /**
     * Call a stored procedure
     *
     * @return array value
     */	
	public function call($procedure) {
		$query = $this->Database->query("CALL $procedure");
		
		if($this->Database->rows() === 0) {
			return FALSE;
		} elseif($this->Database->rows() === 1) {
			$rows[] = $this->Database->fetch();			
		} else {
			while($row = $this->Database->fetch()) {
				$rows[] = $row;	
			}
		}
		
		$this->Database->free();

		if($this->encode) {
			return (isset($rows)) ? $this->encoding($rows) : FALSE;
		} else {
			return (isset($rows)) ? $rows : FALSE;
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
     * Begin transaction
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
	
	public function setLog($ID, $table, $activity, $query = NULL) {		
		if($table !== "muu_users_online_anonymous" and $table !== "muu_users_online") {
			$this->Database->insert(_dbPfx . "logs", "ID_User, ID_Record, Table_Name, Activity, Query, Start_Date", "'". SESSION("ZanUserID") ."', '$ID', '$table', '$activity', '$query', NOW()");
		}
		
		return TRUE;
	}
	
	/**
     * Formatting array
     *
     * @return array value
     */	
	private function encoding($rows) {
		$this->encode = FALSE;
		
		if(is_array($rows)) {
			$key1  = array_keys($rows);
			$size1 = sizeof($key1);			
			
			for($i = 0; $i < $size1; $i++) {
				$key2  = array_keys($rows[$i]);
				$size2 = sizeof($key2);
				
				for($j = 0; $j < $size2; $j++) {					
					$data[$i][$key2[$j]] = encode($rows[$i][$key2[$j]]);								
				}
			}
			
			return $data;
		} else {
			return FALSE;
		}				
	}
}
