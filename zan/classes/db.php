<?php 
/**  
 * ZanPHP  
 * An open source agile and rapid development framework for PHP 5  
 * @package ZanPHP  
 * @author MilkZoft Developer Team  
 * @copyright Copyright (c) 2011, MilkZoft, Inc.  
 * @license http://www.zanphp.com/documentation/en/license/  
 * @link http://www.zanphp.com  
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
	
	private $caching = FALSE;

	/**
	 * 
	 * 
	 * @var private static $connection = FALSE
	 */
	private static $connection = FALSE;
	
	/**
	 * A flag to determinate encoding
	 * 
	 * @var private $encode
	 */
	private $encode = TRUE;
	
	/**
	 * 
	 * 
	 * @var private
	 */
	private $fetchMode = "assoc";
	
	/**
	 * Contains the fields of the table
	 * 
	 * @var private $fields
	 */
	private $fields = "*";
	
	/**
	 * Insert count for transactions
	 * 
	 * @var private $inserts = 0
	 */
	private $inserts = 0;
	
	/**
	 * 
	 * 
	 * @var private
	 */
	private $join = NULL;
	
	/**
	 * Contains the primary key field
	 * 
	 * @var private $primaryKey = FALSE
	 */
	private $primaryKey = FALSE;
	
	
	/**
	 * Contains the query string
	 * 
	 * @var private $query
	 */
	private $query;
	
	/**
	 * Contains the row content in fetch mode
	 * 
	 * @var private $row
	 */
	private $row;
	
	/**
	 * 
	 * 
	 * @var private
	 */
	public $Rs = NULL;
	
	/**
	 * 
	 * 
	 * @var private
	 */
	private $select = "SELECT *";
	
	private $SQL = NULL;
		
	/**
	 * Contains the name of the table
	 * 
	 * @var private $table
	 */
	private $table;
	
	/**
	 * Contains the values of the query
	 * 
	 * @var private $values
	 */
	private $values;
	
	/**
	 * 
	 * 
	 * @var private
	 */
	private $where = NULL;

	private $PDO = FALSE;
	
		
    /**
     * Load Database class
     *
     * @return void
     */
	public function __construct() {
		$this->Cache = $this->core("Cache");

		$this->config("database");
		
		$this->exception("database");
		
		$this->helper(array("exceptions", "string"));
		
		$this->connect();	
	}
			
	/**
     * Begin transaction
     *
     * @return void
     */
	public function begin() {
		return ($this->PDO) ? $this->Database->beginTransaction() : $this->Database->begin();;
	}

    /**
     * Call a stored procedure
     *
     * @return array value
     */	
	public function call($procedure) {	
		if($this->PDO) {	
			$this->Rs = $this->Database->prepare("CALL $procedure");	
			
			$this->Rs->bindParam(1, $data, PDO::PARAM_STR, 4000);
			
			$data = $this->Rs->execute();
		} else {
			$data = $this->Database->query("CALL $procedure");
		}

		if($this->encode) {
			$data = isset($data) ? $this->encoding($data) : FALSE;
		} else { 
			$data = isset($data) ? $data : FALSE;
		}
		
		return $data; 
	}
	
    /**
     * Closes the current database connection
     *
     * @return boolean value / void
     */
	public function close() {
		if($this->PDO) {
			return empty($this->Database);
		} 

		return (!self::$connection) ? FALSE : $this->Database->close(self::$connection);
	}
	
	public function columns($table) {
		$table = $this->getTable($table);
	
		return $this->data("SHOW COLUMNS FROM $table");
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
     * Select database driver and make connection
     *
     * @return void
     */
	public function connect() {
		$this->db = get("db");
		
		if(!file_exists("www/config/database.php")) {
			getException("You must rename and configure your 'config/database.php'");
		}

		if(!self::$connection) {
			$port = ($this->db["dbPort"] === 3306) ? "" : ":". $this->db["dbPort"];
			
			if($this->db["dbPDO"]) {
				self::$connection = TRUE;
				
				$this->PDO = $this->db["dbPDO"];

				if($this->db["dbDriver"] === "mysql" or $this->db["dbDriver"] === "mysqli") {
					try {
					    $this->Database = new PDO("mysql:host=". $this->db["dbHost"] . $port .";dbname=". $this->db["dbName"], $this->db["dbUser"], $this->db["dbPwd"]);
					} catch (PDOException $e) {
					    getException("Database Error: ". $e->getMessage());
					}
				} elseif($this->db["dbDriver"] === "pgsql") {
					try {
					    $this->Database = new PDO("pgsql:host=". $this->db["dbHost"] . $port .";dbname=". $this->db["dbName"], $this->db["dbUser"], $this->db["dbPwd"]);
					} catch (PDOException $e) {
					    getException("Database Error: ". $e->getMessage());
					}
				} elseif($this->db["dbDriver"] === "sqlite") {
					try {
					    $this->Database = new PDO("sqlite:". $this->db["dbFilename"]);
					} catch (PDOException $e) {
					    getException("Database Error: ". $e->getMessage());
					}
				} elseif($this->db["dbDriver"] === "oracle") {
					try {
					    $this->Database = new PDO("OCI:dbname=". $this->db["dbName"] .";charset=UTF-8", $this->db["dbUser"], $this->db["dbPwd"]);
					} catch (PDOException $e) {
					    getException("Database Error: ". $e->getMessage());
					}
				}
			} else {
				if($this->db["dbDriver"] === "mssql") {
					$this->Database   = $this->driver("MsSQL_Db");
					
					self::$connection = $this->Database->connect($this->db);
				} elseif($this->db["dbDriver"] === "mysql") {
					$this->Database   = $this->driver("MySQL_Db");
					
					self::$connection = $this->Database->connect($this->db);			
				} elseif($this->db["dbDriver"] === "mysqli") {
					$this->Database   = $this->driver("MySQLi_Db");
					
					self::$connection = $this->Database->connect($this->db);
				} elseif($this->db["dbDriver"] === "pgsql") {
					$this->Database   = $this->driver("PgSQL_Db");
					
					self::$connection = $this->Database->connect($this->db);
				} elseif($this->db["dbDriver"] === "sqlite") {
					$this->Database   = $this->driver("SQLite_Db");
					
					self::$connection = $this->Database->connect($this->db);
				} elseif($this->db["dbDriver"] === "oracle") {
					$this->Database   = $this->driver("Oracle_Db");
					
					self::$connection = $this->Database->connect($this->db);
				}
			}
		}		
	}
	
    /**
     * Count all records
     *
     * @return integer value
     */	
	public function countAll($table = NULL) {
		$this->table($table);
		
		$query = "SELECT COUNT(*) AS Total FROM $this->table";	
		
		$data = $this->data($query);
		
		return isset($data[0]["Total"]) ? (int) $data[0]["Total"] : 0;
	}

    /**
     * Count records by SQL query
     *
     * @return integer value
     */
	public function countBySQL($SQL, $table = NULL) {		
		if($SQL	=== "") {
			return FALSE;
		}

		$this->table($table);
		
		$query = "SELECT COUNT(*) AS Total FROM $this->table WHERE $SQL";
			
		$data = $this->data($query);
		
		return (isset($data[0]["Total"]) and $data[0]["Total"]) ? (int) $data[0]["Total"] : 0;
	}

	private function data($query) {
		if($query === "") {
			return FALSE;	
		}
		
		$this->Rs = $this->Database->query($query);
		
		if($this->rows() === 0) {
			return FALSE;			
		} else {
			while($row = $this->fetch($this->rows())) {
				$rows[] = $row;	
			}
		}	

		$this->free();
			
		if($this->encode) {
			return isset($rows) ? $this->encoding($rows) : FALSE;
		} else { 
			return isset($rows) ? $rows : FALSE;
		}		
	}
	
    /**
     * Make a delete query by Primary Key
     *
     * @param string $table
     * @param integer $ID
	 * @param string $primaryKey
     * @return boolean value
     */
	public function delete($ID = 0, $table = NULL) {	
		if($ID === 0) {
			return FALSE;		
		}

		if($table) {
			$this->table($table);
		}
		
		$query = "DELETE FROM $this->table WHERE $this->primaryKey = $ID";
		
		return ($this->Database->query($query)) ? TRUE : FALSE;
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
	public function deleteBy($field = NULL, $value = NULL, $table = NULL, $limit = 1) {
		if(!$field or !$value) {
			return FALSE;
		}

		if($table) {
			$this->table($table);
		}
		
		if($this->db["dbDriver"] === "odbc_mssql") {
			$query = "DELETE TOP ($limit) FROM $this->table WHERE $field = '$value'";
		} else {
			$query = "DELETE FROM $this->table WHERE $field = '$value'";
			
			if($limit !== NULL) {
				$query .= " LIMIT $limit";
			}
		}

		return ($this->Database->query($query)) ? TRUE : FALSE;
	}
		
    /**
     * Make a deletion by a SQL query
     *
     * @param string $table
     * @param string $SQL
     * @return boolean value
     */
	public function deleteBySQL($SQL = NULL, $table = NULL) {
		if(!$SQL) {
			return FALSE;
		}

		if($table) {
			$this->table($table);
		}
		
		$query = "DELETE FROM $this->table WHERE $SQL";
		
		return ($this->Database->query($query)) ? TRUE : FALSE;
	}
	
    /**
     * 
     *
     * @param string
     * @return void
     */
	public function encode($encode = TRUE) {
		$this->encode = $encode;
	}
	
	/**
     * Formatting array
     *
     * @return array value
     */	
	private function encoding($rows) {
		$this->encode = TRUE;
		
		if(is_object($rows)) { 
			$array[] = get_object_vars($rows);
	
			$key1  = array_keys($array);
			$size1 = sizeof($key1);			
			
			for($i = 0; $i < $size1; $i++) {
				$key2  = array_keys($array[$i]);
				$size2 = sizeof($key2);
				
				for($j = 0; $j < $size2; $j++) {	
					if($array[$i][$key2[$j]] === "1") {
						$data[$i][$key2[$j]] = 1;
					} elseif($array[$i][$key2[$j]] === "0") {
						$data[$i][$key2[$j]] = 0;
					} else {
						$data[$i][$key2[$j]] = encode($array[$i][$key2[$j]]);								
					}
				}
			}
			
			return $data;			
		} elseif(is_array($rows)) {
			$key1  = array_keys($rows);
			$size1 = sizeof($key1);			
			
			for($i = 0; $i < $size1; $i++) {
				$key2  = array_keys($rows[$i]);
				$size2 = sizeof($key2);
				
				for($j = 0; $j < $size2; $j++) {				
					if($rows[$i][$key2[$j]] === "1") {
						if(stristr($key2[$j], "ID")) {
							$data[$i][$key2[$j]] = 1;
						} else {
							$data[$i][$key2[$j]] = TRUE;
						}
					} elseif($rows[$i][$key2[$j]] === "0") {
						$data[$i][$key2[$j]] = 0;
					} else {
						$data[$i][$key2[$j]] = encode($rows[$i][$key2[$j]]);								
					}								
				}
			}
			
			return $data;
		} else {
			return FALSE;
		}				
	}
	
    /**
     * Gets the results into an array
     *
     * @return boolean value / array value
     */
	public function fetch($count = 0) {
		if($this->PDO) {
			return (!$this->Rs) ? FALSE : $this->Rs->fetch(PDO::FETCH_ASSOC);
		} else {
			return (!$this->Rs) ? FALSE : $this->Database->fetch($count);
		}
	}
	
    /**
     * 
     *
     * @param string
     * @return void
     */
	public function fetchMode($fetch = "assoc") {
		$this->fetchMode = $fetch;	
	}
	
    /**
     * Find record by primary key
     *
     * @param integer $ID
     * @return boolean value / array value
     */
	public function find($ID, $table = NULL, $fields = "*") {
		if($table) {
			$this->table($table, $fields);
		}

		$query = "SELECT $this->fields FROM $this->table WHERE $this->primaryKey = $ID";

		return $this->data($query);
	}
	
    /**
     * Find all records
     *
     * @param string $group = NULL
     * @param string $order = NULL
     * @param string $limit = NULL
     * @return array value
     */
	public function findAll($table = NULL, $fields = "*", $group = NULL, $order = NULL, $limit = NULL) {
		$SQL = NULL;
		
		if($table) {
			$this->table($table, $fields);	
		} 

		if(!is_null($group)) {
			$SQL .= " GROUP BY ". $group;
		}
		
		if(!$order) {
			$SQL .= "";		
		} elseif($order === "DESC") {
			$SQL .= " ORDER BY $this->primaryKey DESC";
		} elseif(!is_null($order)) {
			$SQL .= " ORDER BY ". $order;
		} elseif(is_null($order)) {
			$SQL .= " ORDER BY $this->primaryKey";
		}
	
		if(!is_null($limit)) {
			$SQL .= " LIMIT ". $limit;
		}

		$query = "SELECT $this->fields FROM $this->table$SQL";
		
		return $this->data($query);
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
	public function findBy($field = NULL, $value = NULL, $table = NULL, $fields = "*", $group = NULL, $order = NULL, $limit = NULL) {
		$SQL = NULL;

		if($table) {
			$this->table($table, $fields);
		}
		
		if(!is_null($group)) {
			$SQL .= " GROUP BY ". $group;
		}
		
		if(!$order) {
			$SQL .= "";
		} elseif($order === "DESC") {
			$SQL .= " ORDER BY $this->primaryKey";
		} elseif(!is_null($order)) {
			$SQL .= " ORDER BY ". $order;
		} elseif($order === "") {
			$SQL .= " ORDER BY $this->primaryKey";
		}
		
		if(!is_null($limit)) {
			$SQL .= " LIMIT ". $limit;
		}

		if(is_array($field)) {
			$i = 0;
			$_SQL = NULL;

			foreach($field as $_field => $_value) {
				$_SQL .= "$_field = '$_value' AND ";	
			}
			
			$_SQL = rtrim($_SQL, "AND ");
			
			$query = "SELECT $this->fields FROM $this->table WHERE $_SQL";
		} else {
			$query = "SELECT $this->fields FROM $this->table WHERE $field = '$value'$SQL";
		}

		return $this->data($query);
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
	public function findBySQL($SQL, $table = NULL, $fields = "*", $group = NULL, $order = NULL, $limit = NULL) {		
		if(!is_null($group)) {
			$SQL .= " GROUP BY ". $group;
		}
		
		if($table) {
			$this->table($table, $fields);
		}
		
		if(is_null($order)) { 
			$SQL .= "";		
		} elseif($order === "DESC") {
			$SQL .= " ORDER BY $this->primaryKey DESC";
		} elseif(!is_null($order)) {  
			$SQL .= " ORDER BY ". $order;
		} elseif($order === "") { 
			$SQL .= " ORDER BY $this->primaryKey";
		}

		if($limit) {
			$SQL .= " LIMIT ". $limit;
		}
		
		$query = "SELECT $this->fields FROM $this->table WHERE $SQL";
		
		return $this->data($query);
	}
	
    /**
     * Find the first record
     *
     * @return array value
     */
	public function findFirst($table = NULL, $fields = "*") {
		if($table) {
			$this->table($table, $fields);	
		}

		$query = "SELECT $this->fields FROM $this->table ORDER BY $this->primaryKey ASC LIMIT 1";

		return $this->data($query);	
	}
		
    /**
     * Find the last record
     *
     * @return array value
     */
	public function findLast($table = NULL, $fields = "*") {
		if($table) {
			$this->table($table, $fields);	
		}
	
		$query = "SELECT $this->fields FROM $this->table ORDER BY $this->primaryKey DESC LIMIT 1";

		return $this->data($query);
	}
	
    /**
     * Frees memory
     *
     * @return boolean value / void
     */
	public function free() {
		if($this->PDO) {
	 		return ($this->Rs) ? $this->Rs->closeCursor() : FALSE;
	 	} 

	 	return ($this->Rs) ? $this->Rs->free() : FALSE;
	}
	
    /**
     * 
     *
     * @param string $table
     * @return void
     */
	public function from($table) {
		$table = str_replace($this->db["dbPfx"], "", $table); 
		
		$this->from = $this->db["dbPfx"] . $table;

		return $this;
	}
	
    /**
     * 
     *
     * @param string 
     * @return void
     */
	public function get($table = NULL, $limit = 0, $offset = 0) {
		$table = str_replace($this->db["dbPfx"], "", $table);
		
		if($table !== "") {
			$table = $this->db["dbPfx"] . $table;  
		} else {
			$table = FALSE;	
		}
		
		if($limit === 0 and $offset === 0) {	
			$query = ($table) ? "$this->select FROM $table $this->join $this->where" : "$this->select FROM $this->from $this->join $this->where"; 
		} else {
			$query = ($table) ? "$this->select FROM $table $this->join $this->where LIMIT $limit, $offset" : "$this->select FROM $this->from $this->join $this->where LIMIT $limit, $offset";
		}
	
		return $this->data($query);
	}
	
	public function getTable($table) {
		$table = str_replace($this->db["dbPfx"], "", $table);

		$this->table($table);
		
		return $this->db["dbPfx"] . $table; 	
	}
	
    /**
     * 
     *
     * @param string 
     * @return void
     */
	public function getWhere($table, $where, $limit = 0, $offset = 0) {		
		foreach($where as $field => $value) {
			$_where = "$field = '$value' AND ";
		}
		
		$_where = rtrim($_where, "AND ");
				
		$query = ($limit === 0 and $offset === 0) ? "$this->select FROM $table WHERE $_where" : "SELECT $this->fields FROM $table WHERE $_where LIMIT $limit, $offset"; 
		
		return $this->data($query);
	}
	
    /**
     * Performs a SQL insert
     *
     * @param string $table
     * @param string $fields
     * @param string $values
     * @return object or boolean value
     */		
	public function insert($table = NULL, $data = NULL) {
		if(!$table) {
			if(!$this->table or !$this->fields or !$this->values) {
				return FALSE;
			} else {
				$table  = $this->table;
				$fields = $this->fields;
				$values = $this->values;
			}
		}
		
		$table = $this->getTable($table);
		
		if(is_array($data)) {
			$count   = count($data) - 1;
			$_fields = NULL;
			$_values = NULL;
			$i 		 = 0;
			
			foreach($data as $field => $value) {
				if($i === $count) {
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
			return FALSE;
		}	
	
		$this->Rs = $this->Database->query($query);

		if($this->Rs) {
			$insertID = ($this->PDO) ? $this->Database->lastInsertId() : $this->Database->insertID();
						
			return $insertID;
		}
		
		return FALSE;
	}
	
    /**
     * 
     *
     * @param string 
     * @return void
     */
	public function insertBatch($table, $data) {			
		if(!$table or !$data) {
			return FALSE;
		}

		$table = $this->getTable($table);
		
		if(isset($data[0])) {
			$count   = count($data) - 1;
			$values  = NULL;
			$_fields = NULL;
			$_values = NULL;
			$query   = NULL;
			$i 		 = 0;
			$j 		 = 0;

			foreach($data as $insert) {
				$total = count($data[$i]) - 1;
				
				foreach($insert as $field => $value) {
					if($j === $total) {
						$_fields .= "$field";
						$_values .= "'$value'";
					} else {
						$_fields .= "$field, ";
						$_values .= "'$value', ";	
					}
							
					$j++;	
				}
				
				$values .= ($i === $count) ? "($_values)" : "($_values), ";
			 	
			 	$fields  = $_fields;
				$_fields = NULL;
				$_values = NULL;
				
				$i++;
				$j = 0;
			}

			$query .= "INSERT INTO $table ($fields) VALUES $values;";
		} else {
			return FALSE;
		}

		$inserted = $this->Database->query($query);

		return ($inserted) ? TRUE : FALSE;
	}
	
    /**
     * Gets the last inserted ID
     *
     * @return boolean value / integer value
     */
	public function insertID($table = NULL) {
		if($table) {
			$query = "SELECT TOP 1 $this->primaryKey FROM $this->table ORDER BY $this->primaryKey DESC";
			 	
			$data = $this->data($query);
			
			return $data[0]["$primaryKey"];
		} else {
			return (self::$connection) ? $this->Database->insert_ID() : FALSE;
		}
	}
	
    /**
     * 
     *
     * @param string 
     * @return void
     */
	public function join($table, $condition, $position = FALSE) {
		if(!$table or !$condition) {
			return FALSE;	
		}
		
		$this->join .= (!$position) ? "JOIN $table ON $condition " : "$position JOIN $table ON $condition ";

		return $this;
	}
	
    /**
     * 
     *
     * @param string 
     * @return void
     */
	public function like($data, $match = NULL, $position = "both") {
		if(is_array($data)) {
			$count  = count($data) - 1;
			$_where = NULL;
			$i      = 0;
			
			foreach($data as $field => $value) {
				if($i === $count) {
					if($position === "both") {
						$_where .= "$field LIKE '%$match%'";
					} elseif($position === "before") {
						$_where .= "$field LIKE '%$match'";
					} elseif($postion === "after") {
						$_where .= "$field LIKE '$match%'";
					}
				} else {
					if($position === "both") {
						$_where .= " AND $field LIKE '%$match%'";
					} elseif($position === "before") {
						$_where .= " AND $field LIKE '%$match'";
					} elseif($postion === "after") {
						$_where .= " AND $field LIKE '$match%'";
					}	
				}
			}
		} else {
			if(is_null($this->where)) {
				if($position === "both") {
					$this->where  = "WHERE $data LIKE '%$match%'";
				} elseif($position === "before") {
					$this->where  = "WHERE $data LIKE '%$match'";
				} elseif($position === "after") {
					$this->where  = "WHERE $data LIKE '$match%'";
				}
			} else {
				if($position === "both") {
					$this->where .= " AND $data LIKE '%$match%'";	
				} elseif($position === "before") {
					$this->where .= " AND $data LIKE '%$match'";
				} elseif($position === "after") {
					$this->where .= " AND $data LIKE '$match%'";
				}
			}
		}

		return $this;
	}
	
   	/**
     * 
     *
     * @return void
     */	
	public function notLike($data, $match = FALSE, $position = "both") {
		if(is_array($data)) {
			$count  = count($data) - 1;
			$_where = NULL;
			$i      = 0;
			
			foreach($data as $field => $value) {
				if($i === $count) {
					if($position === "both") {
						$_where .= "$field NOT LIKE '%$match%'";
					} elseif($position === "before") {
						$_where .= "$field NOT LIKE '%$match'";
					} elseif($postion === "after") {
						$_where .= "$field NOT LIKE '$match%'";
					}
				} else {
					if($position === "both") {
						$_where .= " AND $field NOT LIKE '%$match%'";
					} elseif($position === "before") {
						$_where .= " AND $field NOT LIKE '%$match'";
					} elseif($postion === "after") {
						$_where .= " AND $field NOT LIKE '$match%'";
					}	
				}
			}
			
			if(!is_null($this->where)) {
				$this->where .= " OR $field NOT IN ($values)";
			}
		} else {
			if(!is_null($this->where)) {
				$this->where .= " OR $field NOT IN ('$data')";
			}
		}

		return $this;
	}
	
   	/**
     * 
     *
     * @return void
     */	
	public function orLike($data, $match = FALSE, $position = "both") {
		if(is_array($data)) {
			$count  = count($data) - 1;
			$_where = NULL;
			$i      = 0;
			
			foreach($data as $field => $value) {
				if($i === $count) {
					if($position === "both") {
						$_where .= "$field LIKE '%$match%'";
					} elseif($position === "before") {
						$_where .= "$field LIKE '%$match'";
					} elseif($postion === "after") {
						$_where .= "$field LIKE '$match%'";
					}
				} else {
					if($position === "both") {
						$_where .= " $field LIKE '%$match%' OR";
					} elseif($position === "before") {
						$_where .= " $field LIKE '%$match' OR";
					} elseif($postion === "after") {
						$_where .= " $field LIKE '$match%' OR";
					}	
				}
			}
			
			if(!is_null($this->where)) {
				$this->where .= " OR $field NOT IN ($values)";
			}
		} else {
			if(!is_null($this->where)) {
				$this->where .= " OR $field NOT IN ('$data')";
			}
		}

		return $this;
	}
	
   	/**
     * 
     *
     * @return void
     */	
	public function orWhereIn($field, $data) {
		if(is_array($data)) {
			for($i = 0; $i <= count($data) - 1; $i++) {
				$values .= ($i === count($data) - 1) ? "'$data[$i]'" : "'$data[$i]', ";	
			}
			
			if(!is_null($this->where)) {
				$this->where .= " OR $field IN ($values)";
			}
		} else {
			if(!is_null($this->where)) {
				$this->where .= " OR $field IN ('$data')";
			}
		}

		return $this;
	}
	
   	/**
     * 
     *
     * @return void
     */	
	public function orWhereNotIn($field, $data) {
		if(is_array($data)) {
			for($i = 0; $i <= count($data) - 1; $i++) {
				$values .= ($i === count($data) - 1) ? "'$data[$i]'" : "'$data[$i]', ";
			}
			
			if(!is_null($this->where)) {
				$this->where .= " OR $field NOT IN ($values)";
			}
		} else {
			if(!is_null($this->where)) {
				$this->where .= " OR $field NOT IN ('$data')";
			}
		}

		return $this;
	}
	
   /**
     * Make a free query
     *
     * @return void
     */	
	public function query($query) {
		return $this->data($query);
	}
	
	/**
     * Ignore changes
     *
     * @return void
     */	
	public function rollBack() {
		return $this->Database->rollBack();
	}
	
    /**
     * Gets the count of rows
     *
     * @return boolean value / integer value
     */	
	public function rows() {
		if($this->PDO) {
			return (!$this->Rs) ? FALSE : $this->Rs->rowCount();
		} 

		return (!$this->Rs) ? FALSE : $this->Database->rows();
	}
	
    /**
     * Decide whether the system deletes, updates or inserts
     *
     * @param string $option = NULL
     * @return boolean value
     */
	public function save($option = NULL) {	
		if(is_null($option)) {
			return $this->insert();	
		} elseif($option > 0) {
			return $this->update(FALSE, FALSE, $option);	
		} elseif($option === "begin") {
			return $this->insert(TRUE);
		} elseif($option) {
			return $this->updateBySQL();
		} 
	}
	
   	/**
     * 
     *
     * @return void
     */	
	public function select($fields = "*", $normal = TRUE) {
		$this->select = (!$normal) ? $fields : "SELECT $fields";
		$this->_fields = $fields;

		return $this;
	}
	
    /**
     * 
     *
     * @param string 
     * @return void
     */
	public function selectAvg($field, $as = NULL) {
		$this->select = (isset($field) and $as) ? "SELECT AVG($field) as $as" : "SELECT AVG($field) as $field";	

		return $this;
	}
	
    /**
     * 
     *
     * @param string 
     * @return void
     */
	public function selectMax($field, $as = NULL) {
		$this->select = (isset($field) and $as) ? "SELECT MAX($field) as $as" : "SELECT MAX($field) as $field";

		return $this;
	}
	
    /**
     * 
     *
     * @param string 
     * @return void
     */
	public function selectMin($field, $as = NULL) {
		$this->select = (isset($min) and $as) ? "SELECT MIN($field) as $as" : "SELECT MIN($field) as $field";	
	
		return $this;
	}
	
    /**
     * 
     *
     * @param string 
     * @return void
     */
	public function selectSum($field, $as = NULL) {
		$this->select = (isset($field) and $as) ? "SELECT SUM($field) as $as" : "SELECT SUM($field) as $field";	

		return $this;
	}
		
    /**
     * Set table and fields to make a SQL query
     *
     * @param string $table
     * @param string $fields = "*"
     * @return void
     */
	public function table($table, $fields = "*") {
		$fields = is_null($fields) ? "*" : $fields;

		$table = str_replace($this->db["dbPfx"], "", $table);
		
		$this->table  = $this->db["dbPfx"] . $table; 
		$this->fields = $fields;

		$data = $this->data("SHOW COLUMNS FROM $this->table");
		
		if(is_array($data)) {
			foreach($data as $column) {
				if($column["Key"] === "PRI") {
					$this->primaryKey = $column["Field"];
					
					return $this->primaryKey;
				}
			}
		}
		
		return FALSE;
	}
	
    /**
     * Make an update query by Primary Key
     *
     * @param string $table
     * @param string $values
     * @param integer $ID
     * @return boolean value
     */
	public function update($table = NULL, $fields = NULL, $ID = 0, $primaryKey = NULL) {		
		if(!$table or !$fields) {
			if(!$this->table or !$this->fields) {
				return FALSE;
			} else {
				$table  = $this->table;
				$fields = $this->values;				
			}
		}
		
		$table 		= $this->getTable($table);
		$primaryKey = is_null($primaryKey) ? $this->primaryKey : $primaryKey;

		if(is_array($fields)) {
			$count   = count($fields) - 1;
			$_fields = NULL;
			$_values = NULL;
			$i 		 = 0;
			
			foreach($fields as $field => $value) {
				$_values .= "$field = '$value', ";
			}
			
			$_values = rtrim($_values, ", ");
			
			if($ID > 0) {
				$query = "UPDATE $table SET $_values WHERE $primaryKey = '$ID'";	
			} elseif(is_string($ID)) {
				$query = "UPDATE $table SET $_values WHERE $ID";
			} else {
				$query = "UPDATE $table SET $_values";
			}
		} else {		
			if($ID > 0) {
				$query = "UPDATE $table SET $fields WHERE $primaryKey = '$ID'";	
			} elseif(is_string($ID)) {
				$query = "UPDATE $table SET $fields WHERE $ID";	
			} else {
				$query = "UPDATE $table SET $fields";
			}
		}	
		
		$this->Rs = $this->Database->query($query);
		
		if($this->Rs) {
			return TRUE;
		}
		
		return FALSE;
	}
	
    /**
     * Make an update by SQL query
     *
     * @param string $table
     * @param string $SQL
     * @return boolean value
     */
	public function updateBySQL($table = NULL, $SQL = NULL) {
		if(!$table or !$SQL) {
			if(!$this->table or !$this->SQL) {
				return FALSE;
			} else {
				$table = $this->table;
				$SQL   = $this->SQL;	
			}
		}
		
		$table = $this->getTable($table);
		
		$query = "UPDATE $table SET $SQL";
		
		return ($this->Database->query($query)) ? TRUE : FALSE;
	}

    /**
     * Set values for make a insert or update query
     *
     * @param string $values
     * @return void
     */
	public function values($values) {
		$this->values = $values;	

		return $this;
	}
	
    /**
     * 
     *
     * @param string 
     * @return void
     */
	public function where($data, $value = NULL) {
		if(is_array($data)) {
			$count 		 = count($data) - 1;
			$i 			 = 0;
			$_where 	 = NULL;
			$this->where = NULL;
			
			foreach($data as $field => $value) {
				$parts = explode(" ", $field);
				
				if($i === $count) {
					$_where .= (count($parts) === 2) ? "$parts[0] $parts[1] '$value'" : "$field = '$value'";
				} else {
					$_where .= (count($parts) === 2) ? "$parts[0] $parts[1] '$value' AND " : "$field = '$value' AND ";
				}
			
				unset($parts);
				
				$i++;
			}
			
			$this->where = (is_null($this->where)) ? "WHERE $_where" : " AND $_where";
		} else {
			if(isset($data) and !$value) {
				$this->where = (is_null($this->where)) ? "WHERE $data" : " $data";
			} else {
				if(is_null($this->where)) {
					$parts = explode(" ", $data);
					
					$this->where = (count($parts) === 2) ? "WHERE parts[0] $parts[1] '$value'" : "WHERE $data = '$value'";	
				} else {
					$parts = explode(" ", $data);
					
					$this->where .= (count($parts) === 2) ? " AND $parts[0] $parts[1] '$value'" : " AND $data = '$value'";
				}	
			}
		}

		return $this;
	}
	
    /**
     * 
     *
     * @param string 
     * @return void
     */
	public function whereIn($field, $data) {
		if(is_array($data)) {
			$values = NULL;
			
			for($i = 0; $i <= count($data) - 1; $i++) {
				$values .= ($i === count($data) - 1) ? "'$data[$i]'" : "'$data[$i]', ";
			}
			
			if(is_null($this->where)) {
				$this->where = "WHERE $field IN ($values)";
			} else {
				$this->where .= " AND $field IN ($values)";
			}
		} else {
			if(is_null($this->where)) {
				$this->where = "WHERE $field IN ('$data')";
			} else {
				$this->where .= " AND $field IN ('$data')";
			}
		}

		return $this;
	}
	
    /**
     * 
     *
     * @param string 
     * @return void
     */
	public function whereNotIn($field, $data) {
		if(is_array($data)) {
			for($i = 0; $i <= count($data) - 1; $i++) {
				$values .= ($i === count($data) - 1) ? "'$data[$i]'" : "'$data[$i]', ";	
			}
			
			if(is_null($this->where)) {
				$this->where = "WHERE $field NOT IN ($values)";
			} else {
				$this->where .= " AND $field NOT IN ($values)";
			}
		} else {
			if(is_null($this->where)) {
				$this->where = "WHERE $field NOT IN ('$data')";
			} else {
				$this->where .= " AND $field NOT IN ('$data')";
			}
		}

		return $this;
	}
	
    /**
     * 
     *
     * @param string 
     * @return void
     */
	public function whereOr($data, $value = NULL) {
		if(is_array($data)) {
			$count 		 = count($data) - 1;
			$i 			 = 0;
			$_where 	 = NULL;
			$this->where = NULL;
			
			foreach($data as $field => $value) {
				$parts = explode(" ", $field);
				
				if($i === $count) {
					$_where .= (count($parts) === 2) ? "$parts[0] $parts[1] '$value'" : "$field = '$value'";
				} else {
					$_where .= (count($parts) === 2) ? "$parts[0] $parts[1] '$value' OR " : "$field = '$value' OR ";
				}
				
				unset($parts);
				
				$i++;
			}
			
			if(is_null($this->where)) {
				$this->where = "WHERE $_where";
			} else {
				$this->where .= " OR $_where";
			}
		} else {
			if(isset($data) and !$value) {
				if(is_null($this->where)) {
					$this->where  = "WHERE $data";
				} else {
					$this->where .= " $data";	
				}
			} else {
				if(is_null($this->where)) {
					$parts = explode(" ", $data);
					
					$this->where = (count($parts) === 2) ? "WHERE parts[0] $parts[1] '$value'" : "WHERE $data = '$value'";
				} else {
					$parts = explode(" ", $data);
					
					$this->where .= (count($parts) === 2) ? " OR $parts[0] $parts[1] '$value'" : " OR $data = '$value'";
				}	
			}
		}

		return $this;
	}

}
