<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

class ZP_MySQL_Db extends ZP_Load
{
	private static $connection;
	private $SQL;
	
	public function connect()
	{
		if (self::$connection === null) {
			self::$connection = mysql_connect(DB_HOST, DB_USER, DB_PWD);
			mysql_select_db(DB_DATABASE);
		}
		
		return self::$connection;
	}

	public function begin()
	{
		mysql_query(self::$connection, "BEGIN");
		
		return true;
	}

	public function commit()
	{
		return mysql_query(self::$connection, "COMMIT");
	}

	public function rollBack()
	{
		return mysql_query(self::$connection, "ROLLBACK");
	}

	public function query($SQL)
	{
		if ($SQL !== "") {
			if (stristr($SQL, "call") and stripos($SQL, "call") === 0) {
				@mysql_multi_query($SQL, self::$connection);
				
				$this->query = @mysql_store_result(self::$connection);        
            
				if (@mysql_more_results(self::$connection)) {
					@mysql_next_result(self::$connection);            
				}
			} else {
				$this->query = mysql_query($SQL, self::$connection);
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
		return (mysql_query(self::$connection, $query)) ? true : false;
	}

	public function delete($table, $ID, $primaryKey)
	{
		if (!$table or !$ID or !$primaryKey) {
			return false;		
		}
		
		$query = "DELETE FROM $table WHERE $primaryKey = $ID";
		return (mysql_query(self::$connection, $query)) ? true : false;	
	}

	public function deleteBy($table, $field, $value, $limit = "LIMIT 1")
	{	
		if (!$table or !$field or !$value) {
			return false;
		}
		
		if ($limit > 1) {
			$limit = "LIMIT $limit";
		}
		
		$query = "DELETE FROM $table WHERE $field = '$value' $limit";
		return (mysql_query(self::$connection, $query)) ? true : false;
	}
			
	public function deleteBySQL($table, $SQL)
	{
		if (!$table or !$SQL) {
			return false;
		}
		
		$query = "DELETE FROM $table WHERE $SQL";	
		return (mysql_query(self::$connection, $query)) ? true : false;
	}	
	
	public function update($table, $values, $ID, $primaryKey)
	{
		if (!$table or !$values or !$ID or !$primaryKey) {
			return false;
		}
		
		$query = "UPDATE $table SET $values WHERE $primaryKey = '$ID'";
		return (mysql_query(self::$connection, $query)) ? true : false;
	}

	public function updateBySQL($table, $values)
	{
		if (!$table or !$values) {
			return false;		
		}
		
		$query = "UPDATE $table SET $values"; 
		return (mysql_query(self::$connection, $query)) ? true : false;
	}	
	
	public function fetch($type)
	{			
		return (!$this->query) ? false : mysql_fetch_assoc($this->query);	
	}

	public function rows()
	{
		return (!$this->query) ? false : (int) @mysql_num_rows($this->query);	
	}

	public function insertID()
	{
		return mysql_insert_id(self::$connection);
	}

	public function free()
	{
	 	return (!$this->query) ? false : mysql_free_result($this->query);
	}
	
	public function close()
	{
		return (!self::$connection) ? false : mysql_close(self::$connection); 	
	}	
}