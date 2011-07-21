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
	
	/**
	 * Contains the values of the query
	 * 
	 * @var private $values
	 */
	private $logs = _dbLogs;
		
    /**
     * Load Database class
     *
     * @return void
     */
	public function __construct() {
		$this->Database = $this->core("Database");	
	}
	
	public function encode($encode = FALSE) {
		$this->encode = $encode;
	}
	
	public function logs($logs = FALSE) {
		$this->logs = $logs;
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
		 
    /**
     * Calls ZP_ZP_Database::insert to make an insert query
     *
     * @return string "RollBack" / boolean value / integer insert ID
     */
	public function insert($begin = FALSE) {
		if($begin === TRUE) {
			$this->begin();
		}
		
		$query = $this->Database->insert($this->table, $this->fields, $this->values);
		
		if($query === FALSE) {
			return "rollback";
		}
		
		$this->inserts++;				
		
		if($this->primaryKey === FALSE) {
			return TRUE;
		} else {
			$insertID = $this->Database->insertID();
			
			if($this->logs) {	
				$this->setLog($insertID, $this->table, "Insert");
			}
			
			return $insertID;
		}
	}
	
    /**
     * Calls ZP_Database::update to make an update query by primary key
     *
     * @param integer $ID
     * @return boolean value
     */
	public function update($ID) {
		$query = $this->Database->update($this->table, $this->values, $ID, $this->primaryKey);	
		
		if($this->logs) {
			$this->setLog($ID, $this->table, "Update");
		}
		
		return $query;	
	}
	
    /**
     * Calls ZP_Database::updateBySQL to make an update query by SQL query
     *
     * @return boolean value
     */
	public function updateBySQL() {
		$query = $this->Database->updateBySQL($this->table, $this->values);
		
		if($this->logs) {
			$this->setLog(0, $this->table, "UpdateBySQL", $this->values);
		}
				
		return $query;
	}	
	
    /**
     * Calls ZP_Database::delete to do a delete query by primary key
     *
     * @param integer $ID
     * @return boolean value
     */
	public function delete($ID) {
		$query = $this->Database->delete($this->table, $ID, $this->primaryKey);

		if($this->logs) {
			$this->setLog($ID, $this->table, "Delete");
		}
		
		return $query;
	}
	
    /**
     * Calls ZP_Database::deleteBy to do a delete query by specific field
     *
     * @param string $field
     * @param string $value
     * @return boolean value
     */
	public function deleteBy($field, $value) {	
		$query = $this->Database->deleteBy($this->table, $field, $value);	
		
		if($this->logs) {
			$this->setLog(0, $this->table, "DeleteBy", "$field = '$value'");
		}
		
		return $query;
	}	
	
    /**
     * Calls ZP_Database::deleteBySQL to do a delete query by SQL query
     *
     * @param string $SQL
     * @return boolean value
     */
	public function deleteBySQL($SQL) {
		$query = $this->Database->deleteBySQL($this->table, $SQL);
		
		if($this->logs) {
			$this->setLog(0, $this->table, "DeleteBySQL", $SQL);
		}		
		
		return $query;
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
			return $this->encoding($rows);
		} else {
			return $rows;
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
			return $this->encoding($rows);
		} else {
			return $rows;
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
		#
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
