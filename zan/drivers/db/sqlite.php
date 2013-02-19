<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

class ZP_SQLite_Db extends ZP_Load
{
	private static $connection;	
	private $SQL;

	public function connect()
	{
		if (self::$connection === null) {
			$this->Db = self::$connection = new SQLiteDatabase(DB_SQLITE_FILENAME, DB_SQLITE_MODE);
		}
		
		return self::$connection;
	}
	
	public function query($SQL)
	{
		if ($SQL !== "") {
			$this->query = $this->Db->query($SQL, $error);
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
		
		$query = "DELETE FROM $table WHERE $primaryKey = $ID";
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
		return (!$this->query) ? false : $this->query->fetch(SQLITE_ASSOC);	
	}

	public function rows()
	{
		return (!$this->query) ? false : (int) $this->query->numRows();	
	}

	public function insertID()
	{
		return (!$this->query) ? false : (int) $this->query->lastInsertRowId();
	}

	public function free()
	{
	 	return false;
	}

	public function close() {
		return (!self::$connection) ? false : sqlite_close(self::$connection); 	
	}	
}