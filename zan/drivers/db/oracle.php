<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

class ZP_Oracle_Db extends ZP_Load
{
	private static $connection;
	private $SQL;
	
	public function connect()
	{
		if (is_null(self::$connection)) {
			self::$connection = oci_connect(DB_HOST, DB_USER, DB_PWD .":". DB_PORT ."/". DB_DATABASE);
		}
		
		return self::$connection;
	}

	public function commit()
	{
		return oci_commit(self::$connection);
	}

	public function rollBack()
	{
		return oci_rollback(self::$connection);
	}

	public function query($SQL)
	{
		if ($SQL !== "") {
			$query = oci_parse(self::$connection, $SQL);
			$this->query = oci_execute($query);
		}

		return ($this->query) ? $this->query : false;
	}

	public function insert($table, $fields, $values)
	{
		if (!$table or !$fields or !$values) {
			return false;
		}
		
		$query = "INSERT INTO $table ($fields) VALUES ($values)";	
		return ($this->query($query)) ? true : false;
	}

	public function delete($table, $ID, $primaryKey)
	{
		if (!$table or !$ID or !$primaryKey) {
			return false;		
		}
		
		$query = "DELETE FROM $table WHERE $primaryKey = '$ID'";	
		return ($this->query($query)) ? true : false;	
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
		return ($this->query($query)) ? true : false;
	}

	public function deleteBySQL($table, $SQL)
	{
		if (!$table or !$SQL) {
			return false;
		}
		
		$query = "DELETE FROM $table WHERE $SQL";
		return ($this->query($query)) ? true : false;
	}	

	public function update($table, $values, $ID, $primaryKey)
	{
		if (!$table or !$values or !$ID or !$primaryKey) {
			return false;
		}
		
		$query = "UPDATE $table SET $values WHERE $primaryKey = '$ID'";
		return ($this->query($query)) ? true : false;
	}

	public function updateBySQL($table, $values)
	{
		if (!$table or !$values) {
			return false;		
		}
		
		$query = "UPDATE $table SET $values"; 
		return ($this->query($query)) ? true : false;
	}	
	
	public function fetch($type)
	{			
		return (!$this->query) ? false : oci_fetch_assoc($this->query);	
	}

	public function rows()
	{
		return (!$this->query) ? false : (int) oci_num_rows($this->query);	
	}

	public function insertID()
	{
		return 0;
	}

	public function free()
	{
	 	return (!$this->query) ? false : oci_free_statement($this->query);
	}

	public function close()
	{
		return (!self::$connection) ? false : oci_close(self::$connection); 	
	}		
}