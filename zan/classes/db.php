<?php 
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

class ZP_Db extends ZP_Load 
{	
	private $caching = false;
	private $encode = true;
	private $fetchMode = "assoc";
	private $fields = "*";
	private $inserts = 0;
	private $join = null;
	private $primaryKey = false;
	private $query;
	private $row;
	private $select = "SELECT *";
	private $SQL = null;
	private $table;
	private $values;
	private $where = null;
	private $PDO = false;
	private static $connection = false;
	public $Rs = null;

	public function __construct()
	{
		$this->Cache = $this->core("Cache");
		$this->config("database");
		$this->exception("database");
		$this->helper(array("exceptions", "string"));
		$this->connect();
	}

	public function begin() 
	{
		return ($this->PDO) ? $this->Database->beginTransaction() : $this->Database->begin();
	}

	public function call($procedure)
	{
		if ($this->PDO) {
			$this->Rs = $this->Database->prepare("CALL $procedure");

			$this->Rs->bindParam(1, $data, PDO::PARAM_STR, 4000);

			$data = $this->Rs->execute();
		} else {
			$data = $this->Database->call($procedure);
		}

		if ($this->encode) {
			$data = isset($data) ? $this->encoding($data) : false;
		} else { 
			$data = isset($data) ? $data : false;
		}

		return $data;
	}
	
	public function close()
	{
		if ($this->PDO) {
			return empty($this->Database);
		} 

		return (!self::$connection) ? false : $this->Database->close(self::$connection);
	}
	
	public function columns($table)
	{	
		return $this->data("SHOW COLUMNS FROM ". $this->getTable($table) ."");
	}
	
	public function commit()
	{
		return $this->Database->commit();
	}
	
	public function connect()
	{	
		if (!file_exists("www/config/database.php")) {
			getException("You must rename and configure your 'config/database.php'");
		}

		if (!self::$connection) {
			$port = (DB_PORT === 3306) ? "" : ":". DB_PORT;
			
			if (DB_PDO) {
				self::$connection = true;
				
				$this->PDO = DB_PDO;

				if (DB_DRIVER === "mysqli" or DB_DRIVER === "mysql") {
					try {
					    $this->Database = new PDO("mysql:host=". DB_HOST . $port .";dbname=". DB_DATABASE, DB_USER, DB_PWD);
					} catch (PDOException $e) {
					    getException("Database Error: ". $e->getMessage());
					}
				} elseif (DB_DRIVER === "pgsql") {
					try {
					    $this->Database = new PDO("pgsql:host=". DB_HOST . $port .";dbname=". DB_DATABASE, DB_USER, DB_PWD);
					} catch (PDOException $e) {
					    getException("Database Error: ". $e->getMessage());
					}
				} elseif (DB_DRIVER === "sqlite") {
					try {
					    $this->Database = new PDO("sqlite:". DB_SQLITE_FILENAME);
					} catch (PDOException $e) {
					    getException("Database Error: ". $e->getMessage());
					}
				} elseif (DB_DRIVER === "oracle") {
					try {
					    $this->Database = new PDO("OCI:dbname=". DB_DATABASE .";charset=UTF-8", DB_USER, DB_PWD);
					} catch (PDOException $e) {
					    getException("Database Error: ". $e->getMessage());
					}
				}
			} else {
				if (DB_DRIVER === "mysqli") {
					$this->Database = $this->driver("MySQLi_Db");
					self::$connection = $this->Database->connect();
				} elseif (DB_DRIVER === "mysql") {
					$this->Database = $this->driver("MySQL_Db");					
					self::$connection = $this->Database->connect();
				} elseif (DB_DRIVER === "pgsql") {
					$this->Database = $this->driver("PgSQL_Db");					
					self::$connection = $this->Database->connect();
				} elseif (DB_DRIVER === "sqlite") {
					$this->Database = $this->driver("SQLite_Db");					
					self::$connection = $this->Database->connect();
				} elseif (DB_DRIVER === "oracle") {
					$this->Database = $this->driver("Oracle_Db");					
					self::$connection = $this->Database->connect();
				} elseif (DB_DRIVER === "mssql") {
					$this->Database = $this->driver("MsSQL_Db");					
					self::$connection = $this->Database->connect();
				} 
			}
		}
	}
	
	public function countAll($table = null) 
	{
		$this->table($table);

		$query = "SELECT COUNT(1) AS Total FROM $this->table";
		
		$data = $this->data($query);
		
		return isset($data[0]["Total"]) ? (int) $data[0]["Total"] : 0;
	}

	public function countBySQL($SQL, $table = null)
	{
		if ($SQL === "") {
			return false;
		}

		$this->table($table);
		
		$query = "SELECT COUNT(1) AS Total FROM $this->table WHERE $SQL";
		
		$data = $this->data($query);
		
		return (isset($data[0]["Total"]) and $data[0]["Total"]) ? (int) $data[0]["Total"] : 0;
	}

	private function data($query)
	{
		if ($query === "") {
			return false;
		}
		
		$this->Rs = $this->Database->query($query);
		
		if ($this->rows() === 0) {
			return false;
		} else {
			while ($row = $this->fetch($this->rows())) {
				$rows[] = $row;
			}
		}	

		$this->free();
			
		if ($this->encode) {
			return isset($rows) ? $this->encoding($rows) : false;
		} else { 
			return isset($rows) ? $rows : false;
		}		
	}
	
	public function delete($ID = 0, $table = null)
	{	
		if ($ID === 0) {
			return false;
		}

		if ($table) {
			$this->table($table);
		}
		
		return $this->Database->delete($this->table, $ID, $this->primaryKey);
	}
	
	public function deleteBy($field = null, $value = null, $table = null, $limit = 1)
	{
		if (!$field or !$value) {
			return false;
		}

		if ($table) {
			$this->table($table);
		}
		
		return $this->Database->deleteBy($this->table, $field, $value, $limit);
	}
		
	public function deleteBySQL($SQL = null, $table = null)
	{
		if (!$SQL) {
			return false;
		}

		if ($table) {
			$this->table($table);
		}
		
		return $this->Database->deleteBySQL($this->table, $SQL);
	}
	
	public function encode($encode = true) 
	{
		$this->encode = $encode;
	}
	
	private function encoding($rows)
	{
		$this->encode = true;
		
		if (is_object($rows)) {
			$array[] = get_object_vars($rows);
			$key1 = array_keys($array);
			$size1 = sizeof($key1);
			
			for ($i = 0; $i < $size1; $i++) {
				$key2 = array_keys($array[$i]);
				$size2 = sizeof($key2);
				
				for ($j = 0; $j < $size2; $j++) {
					if ($array[$i][$key2[$j]] === "1") {
						$data[$i][$key2[$j]] = 1;
					} elseif ($array[$i][$key2[$j]] === "0") {
						$data[$i][$key2[$j]] = 0;
					} else {
						$data[$i][$key2[$j]] = encode($array[$i][$key2[$j]]);
					}
				}
			}
			
			return $data;
		} elseif (is_array($rows)) {
			$key1 = array_keys($rows);
			$size1 = sizeof($key1);
			
			for ($i = 0; $i < $size1; $i++) {
				$key2 = array_keys($rows[$i]);
				$size2 = sizeof($key2);
				
				for ($j = 0; $j < $size2; $j++) {
					if ($rows[$i][$key2[$j]] === "1") {
						$data[$i][$key2[$j]] = 1;
					} elseif ($rows[$i][$key2[$j]] === "0") {
						$data[$i][$key2[$j]] = 0;
					} else {
						$data[$i][$key2[$j]] = encode($rows[$i][$key2[$j]]);
					}
				}
			}
			
			return $data;
		} else {
			return false;
		}
	}
	
	public function fetch($count = 0)
	{
		if ($this->PDO) {
			return (!$this->Rs) ? false : $this->Rs->fetch(PDO::FETCH_ASSOC);
		} else {
			return (!$this->Rs) ? false : $this->Database->fetch($count);
		}
	}
	
	public function fetchMode($fetch = "assoc")
	{
		$this->fetchMode = $fetch;
	}
	
	public function find($ID, $table = null, $fields = "*")
	{
		if ($table) {
			$this->table($table, $fields);
		}

		$query = "SELECT $this->fields FROM $this->table WHERE $this->primaryKey = '$ID'";

		return $this->data($query);
	}
	
	public function findAll($table = null, $fields = "*", $group = null, $order = null, $limit = null)
	{
		$SQL = null;
		
		if ($table) {
			$this->table($table, $fields);
		} 

		if (!is_null($group)) {
			$SQL .= " GROUP BY ". $group;
		}
		
		if (!$order) {
			$SQL .= "";
		} elseif ($order === "DESC") {
			$SQL .= " ORDER BY $this->primaryKey DESC";
		} elseif (!is_null($order)) {
			$SQL .= " ORDER BY ". $order;
		} elseif (is_null($order)) {
			$SQL .= " ORDER BY $this->primaryKey";
		}
	
		if (!is_null($limit)) {
			$SQL .= " LIMIT ". $limit;
		}

		$query = "SELECT $this->fields FROM $this->table$SQL";
		return $this->data($query);
	}
		
	public function findBy($field = null, $value = null, $table = null, $fields = "*", $group = null, $order = null, $limit = null)
	{
		$SQL = null;

		if ($table) {
			$this->table($table, $fields);
		}
		
		if (!is_null($group)) {
			$SQL .= " GROUP BY ". $group;
		}
		
		if (!$order) {
			$SQL .= "";
		} elseif ($order === "DESC") {
			$SQL .= " ORDER BY $this->primaryKey";
		} elseif (!is_null($order)) {
			$SQL .= " ORDER BY ". $order;
		} elseif ($order === "") {
			$SQL .= " ORDER BY $this->primaryKey";
		}
		
		if (!is_null($limit)) {
			$SQL .= " LIMIT ". $limit;
		}

		if (is_array($field)) {
			$i = 0;
			$_SQL = null;

			foreach ($field as $_field => $_value) {
				$_SQL .= "$_field = '$_value' AND ";
			}
			
			$_SQL = rtrim($_SQL, "AND ");
			$query = "SELECT $this->fields FROM $this->table WHERE $_SQL";
		} else {
			$query = "SELECT $this->fields FROM $this->table WHERE $field = '$value'$SQL";
		}

		return $this->data($query);
	}
	
	public function findBySQL($SQL, $table = null, $fields = "*", $group = null, $order = null, $limit = null)
	{		
		if (!is_null($group)) {
			$SQL .= " GROUP BY ". $group;
		}
		
		if ($table) {
			$this->table($table, $fields);
		}
		
		if (is_null($order)) { 
			$SQL .= "";		
		} elseif ($order === "DESC") {
			$SQL .= " ORDER BY $this->primaryKey DESC";
		} elseif (!is_null($order)) {  
			$SQL .= " ORDER BY ". $order;
		} elseif ($order === "") { 
			$SQL .= " ORDER BY $this->primaryKey";
		}

		if ($limit) {
			$SQL .= " LIMIT ". $limit;
		}
		
		$query = "SELECT $this->fields FROM $this->table WHERE $SQL";
		
		return $this->data($query);
	}
	
	public function findFirst($table = null, $fields = "*")
	{
		if ($table) {
			$this->table($table, $fields);
		}

		$query = "SELECT $this->fields FROM $this->table ORDER BY $this->primaryKey ASC LIMIT 1";
		
		return $this->data($query);
	}
		
	public function findLast($table = null, $fields = "*") 
	{
		if ($table) {
			$this->table($table, $fields);
		}
	
		$query = "SELECT $this->fields FROM $this->table ORDER BY $this->primaryKey DESC LIMIT 1";
		
		return $this->data($query);
	}
	
	public function free()
	{
		if ($this->PDO) {
	 		return ($this->Rs) ? $this->Rs->closeCursor() : false;
	 	} 

	 	return ($this->Rs) ? $this->Rs->free() : false;
	}
	
	public function from($table)
	{
		$table = str_replace(DB_PREFIX, "", $table);
		
		$this->from = DB_PREFIX . $table;
		
		return $this;
	}
	
	public function get($table = null, $limit = 0, $offset = 0)
	{
		$table = str_replace(DB_PREFIX, "", $table);
		$table = ($table !== "") ? DB_PREFIX . $table : ($table !== "");
		
		if ($limit === 0 and $offset === 0) {
			$query = ($table) ? "$this->select FROM $table $this->join $this->where" : "$this->select FROM $this->from $this->join $this->where";
		} else {
			if ($table) { 
				$query = "$this->select FROM $table $this->join $this->where LIMIT $limit, $offset"; 
			} else {
				$query = "$this->select FROM $this->from $this->join $this->where LIMIT $limit, $offset";
			}
		}

		$this->cleanUp();
	
		return $this->data($query);
	}

	public function cleanUp()
	{
		$this->select = null;
		$this->from   = null;
		$this->join   = null;
		$this->where  = null;

		return true;
	}
	
	public function getTable($table)
	{
		$table = str_replace(DB_PREFIX, "", $table);

		$this->table($table);
		
		return DB_PREFIX . $table;
	}
	
	public function getWhere($table, $where, $limit = 0, $offset = 0)
	{		
		foreach ($where as $field => $value) {
			$_where = "$field = '$value' AND ";
		}
		
		$_where = rtrim($_where, "AND ");
		$table = $this->getTable($table);
		
		if ($limit === 0 and $offset === 0) {
			$query = "$this->select FROM $table WHERE $_where"; 
		} else {
			$query = "SELECT $this->fields FROM $table WHERE $_where LIMIT $limit, $offset";
		}

		return $this->data($query);
	}
	
	public function insert($table = null, $data = null)
	{
		if (!$table) {
			if (!$this->table or !$this->fields or !$this->values) {
				return false;
			} else {
				$table = $this->table;
				$fields = $this->fields;
				$values = $this->values;
			}
		}
		
		$table = $this->getTable($table);
		
		if (is_array($data)) {
			$count = count($data) - 1;
			$_fields = null;
			$_values = null;
			$i = 0;
			
			foreach ($data as $field => $value) {
				if ($i === $count) {
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
			return false;
		}	
		
		$this->Rs = $this->Database->insert($table, $_fields, $_values);

		if ($this->Rs) {
			return ($this->PDO) ? $this->Database->lastInsertId() : $this->Database->insertID();
		}
		
		return false;
	}
	
	public function insertBatch($table, $data)
	{			
		if (!$table or !$data) {
			return false;
		}

		$table = $this->getTable($table);
		
		if (isset($data[0])) {
			$count = count($data) - 1;
			$values = null;
			$_fields = null;
			$_values = null;
			$query = null;
			$i = 0;
			$j = 0;

			foreach ($data as $insert) {
				$total = count($data[$i]) - 1;
				
				foreach ($insert as $field => $value) {
					if ($j === $total) {
						$_fields .= "$field";
						$_values .= "'$value'";
					} else {
						$_fields .= "$field, ";
						$_values .= "'$value', ";
					}

					$j++;
				}
				
				$values .= ($i === $count) ? "($_values)" : "($_values), ";
			 	$fields = $_fields;
				$_fields = null;
				$_values = null;
				
				$i++;
				$j = 0;
			}

			$query .= "INSERT INTO $table ($fields) VALUES $values;";
		} else {
			return false;
		}

		return ($this->Database->query($query)) ? true : false;
	}
	
	public function insertID($table = null)
	{
		if ($table) {
			$query = "SELECT TOP 1 $this->primaryKey FROM $this->table ORDER BY $this->primaryKey DESC";
			$data = $this->data($query);

			return $data[0]["$primaryKey"];
		} else {
			return (self::$connection) ? $this->Database->insert_ID() : false;
		}
	}
	
	public function join($table, $condition, $position = false)
	{
		if (!$table or !$condition) {
			return false;
		}
		
		$this->join .= (!$position) ? "JOIN $table ON $condition " : "$position JOIN $table ON $condition ";
		
		return $this;
	}
	
	public function like($data, $match = null, $position = "both")
	{
		if (is_array($data)) {
			$count = count($data) - 1;
			$_where = null;
			$i = 0;
			
			foreach ($data as $field => $value) {
				if ($i === $count) {
					if ($position === "both") {
						$_where .= "$field LIKE '%$match%'";
					} elseif ($position === "before") {
						$_where .= "$field LIKE '%$match'";
					} elseif ($postion === "after") {
						$_where .= "$field LIKE '$match%'";
					}
				} else {
					if ($position === "both") {
						$_where .= " AND $field LIKE '%$match%'";
					} elseif ($position === "before") {
						$_where .= " AND $field LIKE '%$match'";
					} elseif ($postion === "after") {
						$_where .= " AND $field LIKE '$match%'";
					}
				}
			}
		} else {
			if (is_null($this->where)) {
				if ($position === "both") {
					$this->where = "WHERE $data LIKE '%$match%'";
				} elseif ($position === "before") {
					$this->where = "WHERE $data LIKE '%$match'";
				} elseif ($position === "after") {
					$this->where = "WHERE $data LIKE '$match%'";
				}
			} else {
				if ($position === "both") {
					$this->where .= " AND $data LIKE '%$match%'";
				} elseif ($position === "before") {
					$this->where .= " AND $data LIKE '%$match'";
				} elseif ($position === "after") {
					$this->where .= " AND $data LIKE '$match%'";
				}
			}
		}

		return $this;
	}
	
	public function notLike($data, $match = false, $position = "both")
	{
		if (is_array($data)) {
			$count = count($data) - 1;
			$_where = null;
			$i = 0;
			
			foreach ($data as $field => $value) {
				if ($i === $count) {
					if ($position === "both") {
						$_where .= "$field NOT LIKE '%$match%'";
					} elseif ($position === "before") {
						$_where .= "$field NOT LIKE '%$match'";
					} elseif ($postion === "after") {
						$_where .= "$field NOT LIKE '$match%'";
					}
				} else {
					if ($position === "both") {
						$_where .= " AND $field NOT LIKE '%$match%'";
					} elseif ($position === "before") {
						$_where .= " AND $field NOT LIKE '%$match'";
					} elseif ($postion === "after") {
						$_where .= " AND $field NOT LIKE '$match%'";
					}	
				}
			}
			
			if (!is_null($this->where)) {
				$this->where .= " OR $field NOT IN ($values)";
			}
		} else {
			if (!is_null($this->where)) {
				$this->where .= " OR $field NOT IN ('$data')";
			}
		}

		return $this;
	}
		
	public function orLike($data, $match = false, $position = "both")
	{
		if (is_array($data)) {
			$count = count($data) - 1;
			$_where = null;
			$i = 0;
			
			foreach ($data as $field => $value) {
				if ($i === $count) {
					if ($position === "both") {
						$_where .= "$field LIKE '%$match%'";
					} elseif ($position === "before") {
						$_where .= "$field LIKE '%$match'";
					} elseif ($postion === "after") {
						$_where .= "$field LIKE '$match%'";
					}
				} else {
					if ($position === "both") {
						$_where .= " $field LIKE '%$match%' OR";
					} elseif ($position === "before") {
						$_where .= " $field LIKE '%$match' OR";
					} elseif ($postion === "after") {
						$_where .= " $field LIKE '$match%' OR";
					}	
				}
			}
			
			if (!is_null($this->where)) {
				$this->where .= " OR $field NOT IN ($values)";
			}
		} else {
			if (!is_null($this->where)) {
				$this->where .= " OR $field NOT IN ('$data')";
			}
		}

		return $this;
	}
	
	public function orWhereIn($field, $data)
	{
		if (is_array($data)) {
			for ($i = 0; $i <= count($data) - 1; $i++) {
				$values .= ($i === count($data) - 1) ? "'$data[$i]'" : "'$data[$i]', ";
			}
			
			if (!is_null($this->where)) {
				$this->where .= " OR $field IN ($values)";
			}
		} else {
			if (!is_null($this->where)) {
				$this->where .= " OR $field IN ('$data')";
			}
		}

		return $this;
	}
		
	public function orWhereNotIn($field, $data)
	{
		if (is_array($data)) {
			for ($i = 0; $i <= count($data) - 1; $i++) {
				$values .= ($i === count($data) - 1) ? "'$data[$i]'" : "'$data[$i]', ";
			}
			
			if (!is_null($this->where)) {
				$this->where .= " OR $field NOT IN ($values)";
			}
		} else {
			if (!is_null($this->where)) {
				$this->where .= " OR $field NOT IN ('$data')";
			}
		}

		return $this;
	}
		
	public function query($query) 
	{
		return $this->data($query);
	}
	
	public function rollBack() 
	{
		return $this->Database->rollBack();
	}
	
	public function rows()
	{
		if ($this->PDO) {
			return (!$this->Rs) ? false : $this->Rs->rowCount();
		} 

		return (!$this->Rs) ? false : $this->Database->rows();
	}
	
	public function save($option = null) 
	{	
		if (is_null($option)) {
			return $this->insert();	
		} elseif ($option > 0) {
			return $this->update(false, false, $option);
		} elseif ($option === "begin") {
			return $this->insert(true);
		} elseif ($option) {
			return $this->updateBySQL();
		} 
	}
	
	public function select($fields = "*", $normal = true)
	{
		$this->select = (!$normal) ? $fields : "SELECT $fields";
		$this->_fields = $fields;

		return $this;
	}

	public function selectAvg($field, $as = null)
	{
		$this->select = (isset($field) and $as) ? "SELECT AVG($field) as $as" : "SELECT AVG($field) as $field";
		
		return $this;
	}

	public function selectMax($field, $as = null)
	{
		$this->select = (isset($field) and $as) ? "SELECT MAX($field) as $as" : "SELECT MAX($field) as $field";
		
		return $this;
	}

	public function selectMin($field, $as = null)
	{
		$this->select = (isset($min) and $as) ? "SELECT MIN($field) as $as" : "SELECT MIN($field) as $field";
		
		return $this;
	}

	public function selectSum($field, $as = null)
	{
		$this->select = (isset($field) and $as) ? "SELECT SUM($field) as $as" : "SELECT SUM($field) as $field";
		
		return $this;
	}

	public function table($table, $fields = "*")
	{
		$fields = is_null($fields) ? "*" : $fields;
		$table = str_replace(DB_PREFIX, "", $table);
		
		$this->table = DB_PREFIX . $table; 
		$this->fields = $fields;
		
		$data = $this->data("SHOW COLUMNS FROM $this->table");

		if (is_array($data)) {
			foreach ($data as $column) {
				if ($column["Key"] === "PRI") {
					$this->primaryKey = $column["Field"];

					return $this->primaryKey;
				}
			}
		}

		return false;
	}

	public function update($table = null, $fields = null, $ID = 0, $primaryKey = null)
	{
		if (!$table or !$fields) {
			if (!$this->table or !$this->fields) {
				return false;
			} else {
				$table = $this->table;
				$fields = $this->values;
			}
		}

		$table = $this->getTable($table);
		$primaryKey = is_null($primaryKey) ? $this->primaryKey : $primaryKey;

		if (is_array($fields)) {
			$count = count($fields) - 1;
			$_fields = null;
			$_values = null;
			$i = 0;

			foreach ($fields as $field => $value) {
				if (is_null($value)) {
					$_values .= "$field = DEFAULT, ";
				} else {
					$_values .= "$field = '$value', ";
				}
			}

			$_values = rtrim($_values, ", ");

			if ($ID > 0) {
				$query = "UPDATE $table SET $_values WHERE $primaryKey = '$ID'";
			} elseif (is_string($ID)) {
				$query = "UPDATE $table SET $_values WHERE $ID";
			} else {
				$query = "UPDATE $table SET $_values";
			}
		} else {
			if ($ID > 0) {
				$query = "UPDATE $table SET $fields WHERE $primaryKey = '$ID'";
			} elseif (is_string($ID)) {
				$query = "UPDATE $table SET $fields WHERE $ID";
			} else {
				$query = "UPDATE $table SET $fields";
			}
		}

		$this->Rs = $this->Database->query($query);
		
		if ($this->Rs) {
			return true;
		}

		return false;
	}

	public function updateBySQL($table = null, $SQL = null)
	{
		if (!$table or !$SQL) {
			if (!$this->table or !$this->SQL) {
				return false;
			} else {
				$table = $this->table;
				$SQL = $this->SQL;
			}
		}

		$table = $this->getTable($table);

		return $this->Database->updateBySQL($table, $SQL);
	}

	public function values($values)
	{
		$this->values = $values;

		return $this;
	}
	
	public function where($data, $value = null)
	{
		if (is_array($data)) {
			$count = count($data) - 1;
			$i = 0;
			$_where = null;
			$this->where = null;

			foreach ($data as $field => $value) {
				$parts = explode(" ", $field);

				if ($i === $count) {
					$_where .= (count($parts) === 2) ? "$parts[0] $parts[1] '$value'" : "$field = '$value'";
				} else {
					$_where .= (count($parts) === 2) ? "$parts[0] $parts[1] '$value' AND " : "$field = '$value' AND ";
				}

				unset($parts);
				$i++;
			}

			$this->where = (is_null($this->where)) ? "WHERE $_where" : " AND $_where";
		} else {
			if (isset($data) and !$value) {
				$this->where = (is_null($this->where)) ? "WHERE $data" : " $data";
			} else {
				if (is_null($this->where)) {
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
	
	public function whereIn($field, $data)
	{
		if (is_array($data)) {
			$values = null;

			for ($i = 0; $i <= count($data) - 1; $i++) {
				$values .= ($i === count($data) - 1) ? "'$data[$i]'" : "'$data[$i]', ";
			}

			if (is_null($this->where)) {
				$this->where = "WHERE $field IN ($values)";
			} else {
				$this->where .= " AND $field IN ($values)";
			}
		} else {
			if (is_null($this->where)) {
				$this->where = "WHERE $field IN ('$data')";
			} else {
				$this->where .= " AND $field IN ('$data')";
			}
		}

		return $this;
	}
	
	public function whereNotIn($field, $data)
	{
		if (is_array($data)) {
			for ($i = 0; $i <= count($data) - 1; $i++) {
				$values .= ($i === count($data) - 1) ? "'$data[$i]'" : "'$data[$i]', ";
			}

			if (is_null($this->where)) {
				$this->where = "WHERE $field NOT IN ($values)";
			} else {
				$this->where .= " AND $field NOT IN ($values)";
			}
		} else {
			if (is_null($this->where)) {
				$this->where = "WHERE $field NOT IN ('$data')";
			} else {
				$this->where .= " AND $field NOT IN ('$data')";
			}
		}

		return $this;
	}
	
	public function whereOr($data, $value = null) 
	{
		if (is_array($data)) {
			$count = count($data) - 1;
			$i = 0;
			$_where = null;
			$this->where = null;
			
			foreach ($data as $field => $value) {
				$parts = explode(" ", $field);
				
				if ($i === $count) {
					$_where .= (count($parts) === 2) ? "$parts[0] $parts[1] '$value'" : "$field = '$value'";
				} else {
					$_where .= (count($parts) === 2) ? "$parts[0] $parts[1] '$value' OR " : "$field = '$value' OR ";
				}

				unset($parts);

				$i++;
			}

			if (is_null($this->where)) {
				$this->where = "WHERE $_where";
			} else {
				$this->where .= " OR $_where";
			}
		} else {
			if (isset($data) and !$value) {
				if (is_null($this->where)) {
					$this->where  = "WHERE $data";
				} else {
					$this->where .= " $data";
				}
			} else {
				if (is_null($this->where)) {
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