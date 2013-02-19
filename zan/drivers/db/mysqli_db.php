<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

class ZP_MySQLi_Db extends ZP_Load
{
	private static $connection;
	private $SQL;
	
	public function connect()
	{
		if (!self::$connection) {
			self::$connection = mysqli_connect(DB_HOST, DB_USER, DB_PWD, DB_DATABASE);
		}
	
		return self::$connection;
	}

	public function begin()
	{
		return mysqli_query(self::$connection, "BEGIN");
	}
	
	public function commit()
	{
		return mysqli_query(self::$connection, "COMMIT");
	}
	
	public function rollBack()
	{
		return mysqli_query(self::$connection, "ROLLBACK");
	}
	
	public function call($procedure)
	{
		return $this->query("CALL $procedure");
	}	

	public function query($SQL)
	{
		if ($SQL !== "") {
			if (stristr($SQL, "call") and stripos($SQL, "call") === 0) {
				mysqli_multi_query(self::$connection, $SQL);
				
				$this->query = mysqli_store_result(self::$connection);        
            
				if (mysqli_more_results(self::$connection)) {
					mysqli_next_result(self::$connection);            
				}        
			} else {
				$this->query = mysqli_query(self::$connection, $SQL);
			}			
		}
		
		return ($this->query) ? $this->query : false;
	}
	
	public function insert($table, $fields, $values)
	{
		if (!$table or !$fields or !$values) {
			return false;
		}
		
		$query = "INSERT INTO $table ($fields) VALUES ($values)";

		return (mysqli_query(self::$connection, $query)) ? true : false;
	}

	public function delete($table, $ID, $primaryKey)
	{
		if (!$table or !$ID or !$primaryKey) {
			return false;		
		}
		
		$query = "DELETE FROM $table WHERE $primaryKey = '$ID'";	
		
		return (mysqli_query(self::$connection, $query)) ? true : false;	
	}

	public function deleteBy($table, $field, $value, $limit = "LIMIT 1")
	{
		if (!$table or !$field or !$value) {
			return false;
		}

		if (DB_DRIVER === "odbc_mssql") {
			$query = "DELETE TOP ($limit) FROM $table WHERE $field = '$value'";
		} else {
			$query = "DELETE FROM $table WHERE $field = '$value'";
			
			if ($limit !== null) {
				$query .= " LIMIT $limit";
			}
		}
		
		$query = "DELETE FROM $table WHERE $field = '$value' $limit";

		return (mysqli_query(self::$connection, $query)) ? true : false;
	}

	public function deleteBySQL($table, $SQL)
	{
		if (!$table or !$SQL) {
			return false;
		}
		
		$query = "DELETE FROM $table WHERE $SQL";	
		
		return (mysqli_query(self::$connection, $query)) ? true : false;
	}	

	public function update($table, $values, $ID, $primaryKey)
	{
		if (!$table or !$values or !$ID or !$primaryKey) {
			return false;
		}
		
		$query = "UPDATE $table SET $values WHERE $primaryKey = $ID";		
		
		return (mysqli_query(self::$connection, $query)) ? true : false;
	}

	public function updateBySQL($table, $SQL)
	{
		if (!$table or !$SQL) {
			return false;		
		}
		
		$query = "UPDATE $table SET $SQL"; 
		
		return (mysqli_query(self::$connection, $query)) ? true : false;
	}	
	
	public function fetch($type)
	{
		return (!$this->query) ? false : mysqli_fetch_assoc($this->query);	
	}

	public function rows()
	{
		$rows = ($this->query) ? (int) mysqli_num_rows($this->query) : false;
		
		return (!$this->query) ? false : $rows;	
	}

	public function insertID()
	{
		return mysqli_insert_id(self::$connection);
	}

	public function free()
	{
	 	return (!$this->query) ? false : mysqli_free_result($this->query);
	}

	public function close()
	{
		return (!self::$connection) ? false : mysqli_close(self::$connection); 	
	}	
}