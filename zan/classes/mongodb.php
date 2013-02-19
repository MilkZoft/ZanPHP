<?php
if (!defined("ACCESS")) {
    die("Error: You don't have permission to access here...");
}

class ZP_MongoDB extends ZP_Load
{		
	private $collection = null;
	private $condition = false;
	private static $connection = false;
	private $data = false;
	private $fields;
	private $hint = false;
	public $json = null;
	private $limit = false;
	private $query;
	private $row;	
	private $skip = false;
	private $sort = array("_id" => 1);
	private $values;

	public function __construct() 
	{		
		$this->helper("debugging");	
		$this->config("database");
		$this->connect();	
	}

	public function collection($collection = null)
	{
		if (!is_null($collection)) {
			$this->collection = $collection;
		}
	}

	public function command($command)
	{
		$this->Mongo->selectCollection($this->db["dbNoSQLDatabase"], $this->collection)->command($command);
	}

	public function connect()
	{
		if (!self::$connection) {
			try {
				$this->db = get("db");
				$this->Mongo = new Mongo("mongodb://". $this->db["dbNoSQLHost"] .":". $this->db["dbNoSQLPort"]);
				
				if (!$this->Mongo) {
					throw new Exception(e("Connection Error"), 1);
				}
				
				if ($this->db["dbNoSQLUser"] !== "" and $this->db["dbNoSQLPwd"] !== "") {
					$this->Mongo->selectDb($this->db["dbNoSQLDatabase"])->authenticate($this->db["dbNoSQLUser"], $this->db["dbNoSQLPwd"]);
				} else {
					$this->Mongo->selectDb($this->db["dbNoSQLDatabase"]);
				}
			} catch(Exception $e) {
				getException($e);	
			}
		}									
	}

	public function countAll()
	{
		if (is_object($this->Cursor)) {
			return $this->Cursor->count();
		} else {
			if ($this->collection) {
		 		$this->find();	 		
		 		return $this->Cursor->count();	
			} else {
				return false;
			}
		} 
	}

	public function countByQuery($query)
	{		
		$this->find($query, false);		
		return (is_object($this->Cursor)) ? $this->Cursor->count() : false;
	}

	private function data()
	{
		if ($this->Cursor->count() === 0) {
			return false;			
		} else {
			$this->Cursor->sort($this->sort);
			
			if ($this->limit > 0) {
				$this->Cursor->limit($this->limit);
				$this->limit = false;
			}
			
			if ($this->skip > 0) {
				$this->Cursor->skip($this->skip);
			}
			
			if (is_array($this->hint)) {
				$this->Cursor->hint($this->hint);
			}
			
			$data = iterator_to_array($this->Cursor, false);
		}	

		return $data;
	}

	public function delete($criteria, $justOne = true, $safe = true)
	{	
		if (is_null($this->collection) or !$criteria) {
			return false;
		}
		
		if ($justOne and $safe) {
			$options = array("justOne" => true, "safe" => true);
		} elseif ($justOne) {
			$options = array("justOne" => true);
		} elseif ($safe) {
			$options = array("safe" => true);
		}
	
		$this->Mongo->selectCollection($this->db["dbNoSQLDatabase"], $this->collection)->remove($criteria, $options);
		return true;
	}
	
	public function deleteBy($field = false, $value = false, $justOne = true, $safe = true)
	{
		return $this->delete(array($field => $value), $justOne, $safe);
	}

	public function deleteFile($_id)
	{
		$GridFS = $this->Mongo->selectCollection($this->db["dbNoSQLDatabase"], $this->collection)->getGridFS();
		$ID = new MongoId($_id);
		$GridFS->delete($ID);
		return true;
	}
	
	public function drop($collection = null)
	{
		if ($collection) {
			$this->Mongo->selectCollection($this->db["dbNoSQLDatabase"], $collection)->drop();
		} else {
			$this->Mongo->selectCollection($this->db["dbNoSQLDatabase"], $this->collection)->drop();	
		}
		
		return true;
	}

	public function ensureIndex($index = false, $order = "ASC", $unique = false)
	{
		if ($index and $order === "ASC" and $unique) {
			$this->Mongo->ensureIndex(array($index => 1), array("unique" => true));
		} elseif ($index and $order === "DESC" and $unique) {
			$this->Mongo->ensureIndex(array($index => -1), array("unique" => true));
		} elseif ($index and $order === "ASC") {
			$this->Mongo->ensureIndex(array($index => 1));
		} elseif ($index and $order === "DESC") {
			$this->Mongo->ensureIndex(array($index => -1));
		}
		
		return false;
	}

	public function find($query = null, $collection = null)
	{
		$this->collection = isset($collection) ? $collection : $this->collection;

		if (is_null($query)) {
			$this->Cursor = $this->Mongo->selectCollection($this->db["dbNoSQLDatabase"], $this->collection)->find();
		} else { 
			if (!is_array($query) and is_string($query)) {
				$query = json_decode($query, true);
			}
			
			if ($this->condition) {
				$this->Cursor = $this->Mongo->selectCollection($this->db["dbNoSQLDatabase"], $this->collection)->find($query, $this->condition);
				unset($this->condition);
				$this->condition = false;
			}
		
			$this->Cursor = $this->Mongo->selectCollection($this->db["dbNoSQLDatabase"], $this->collection)->find($query);
		}
		
		return $this->data();
	}
	
	public function findAll($collection = null, $group = null, $order = null, $limit = null)
	{
		if ($collection) {
			$this->collection = $collection;	
		}

		if (is_null($this->collection)) {
			return false;
		}
		
		$this->Cursor = $this->Mongo->selectCollection($this->db["dbNoSQLDatabase"], $this->collection)->find();
	
		return $this->data();		
	}

	public function findBy($field, $value, $collection = null)
	{
		if ($collection) {
			$this->collection = $collection;	
		}

		if (is_null($this->collection)) {
			return false;
		}
		
		$this->Cursor = $this->Mongo->selectCollection($this->db["dbNoSQLDatabase"], $this->collection)->find(array($field => $value));
	
		return $this->data();
	}
	
	public function findByID($ID, $collection = null)
	{
		if ($collection) {
			$this->collection = $collection;	
		}

		if (is_null($this->collection)) {
			return false;
		}
		
		if (strlen($ID) === 24) {
			$ID = new MongoId($ID);
		}
		
		$this->Cursor = $this->Mongo->selectCollection($this->db["dbNoSQLDatabase"], $this->collection)->find(array("_id" => $ID));
		return $this->data();
	}

	public function findFirst($collection = null)
	{
		if ($collection) {
			$this->collection = $collection;	
		}

		if (is_null($this->collection)) {
			return false;
		}
		
		$this->Cursor = $this->Mongo->selectCollection($this->db["dbNoSQLDatabase"], $this->collection)->findOne();
		return $this->data();
	}

	public function findLast($collection = null)
	{
		if ($collection) {
			$this->collection = $collection;	
		}

		if (is_null($this->collection)) {
			return false;
		}
				
		$this->Cursor = $this->Mongo->selectCollection($this->db["dbNoSQLDatabase"], $this->collection)->find();	
		$this->sort("_id", "DESC");
		$this->limit(1);
		return $this->data();
	}

	public function get($type = "AND", $array, $return = false, $add = true)
	{
		if ($add and !$return) {
			if ($type === "AND") {
				foreach ($array as $field => $value) {
					$this->json .= '"'. $field .'" : "'. $value .'", ';
				}
			} elseif ($type === "OR") {
				$keys = array_keys($array);
				
				$j = 0;
				$or = false;

				foreach ($array as $values) {
					if (is_array($values)) {
						for ($i = 0; $i <= count($values) - 1; $i++) {
							if (!$or) {
								$last = substr($this->json, strlen($this->json) - 1, strlen($this->json));
								
								if ($last === "]") {
									$this->json .= ', "$or":[{';
								} else {
									$this->json .= '"$or":[{';
								}
								
								$or = true;
							}
							
							if ($j === count($keys) - 1) {
								$this->json .= '"'. $keys[$j] .'" : "'. $values[$i] .'"';
							} else {
								$this->json .= '"'. $keys[$j] .'" : "'. $values[$i] .'", ';
							}
						}
					}
					
					$j++;
				}
				
				$this->json .= '}]';
			}
		} else {
			if ($type === "AND") {
				$i = 0;

				foreach ($array as $field => $value) {
					if ($i === count($array) - 1) {
						$last = substr($this->json, strlen($this->json) - 1, strlen($this->json));

						if ($last === "]") {
							$this->json .= ', "'. $field .'" : "'. $value .'"';
						} else {
							$this->json .= '"'. $field .'" : "'. $value .'"';
						} 
					} else {
						$last = substr($this->json, strlen($this->json) - 1, strlen($this->json));
						
						if ($last === "]") {
							$this->json .= ', "'. $field .'" : "'. $value .'", ';
						} else {
							$this->json .= '"'. $field .'" : "'. $value .'", ';
						}
					}
				}
			} elseif ($type === "OR") {
				$keys = array_keys($array);
				$j = 0;
				$or = false;

				foreach ($array as $values) {
					if (is_array($values)) {
						for ($i = 0; $i <= count($values) - 1; $i++) {
							if (!$or) {
								$last = substr($this->json, strlen($this->json) - 1, strlen($this->json));
								
								if ($last === "]") {
									$this->json .= ', "$or":[{';
								} else {
									$this->json .= '"$or":[{';
								}
								
								$or = true;
							}
							
							if ($j === count($keys) - 1) {
								$this->json .= '"'. $keys[$j] .'" : "'. $values[$i] .'"';
							} else {
								$this->json .= '"'. $keys[$j] .'" : "'. $values[$i] .'", ';
							}
						}
					}
					
					$j++;
				}
				
				$this->json .= '}]';
			}
		}
		
		if ($return) {
			$return = $this->json;
			empty($this->json);
			return "{". $return ."}";
		}
	}

	public function getAllFiles($collection = null)
	{
		if ($collection) {
			$this->collection = $collection;	
		}

		if (is_null($this->collection)) {
			return false;
		}

		$GridFS = $this->Mongo->selectCollection($this->db["dbNoSQLDatabase"], $this->collection)->getGridFS();
		$Cursor = $GridFS->find();
		$i = 0;
		
		foreach ($Cursor as $Object) {
			$files[$i]["filename"] = $Object->getFilename();
			$files[$i]["content"] = $Object->getBytes();
			$i++;
		}
		
		return $files;
	}
	
	public function getFile($_id, $collection = null, $mimeType = "image/jpeg", $return = false)
	{
		if ($collection) {
			$this->collection = $collection;	
		}

		if (is_null($this->collection)) {
			return false;
		}

		$GridFS = $this->Mongo->selectCollection($this->db["dbNoSQLDatabase"], $this->collection)->getGridFS();
		$ID = new MongoId($_id);
		$file = $GridFS->findOne(array("_id" => $ID));
		
		if ($return) {
			return $file;
		} else {
			header("Content-Type: $mimeType");
			print $file->getBytes();
			exit;	
		}	
	}

	public function getLastID($collection = null)
	{
		if ($collection) {
			$this->collection = $collection;	
		}

		if (is_null($this->collection)) {
			return false;
		}

		$this->Cursor = $this->Mongo->selectCollection($this->db["dbNoSQLDatabase"], $this->collection)->find();
		$this->sort("_id", "DESC");
		$this->limit(1);
		$data = $this->data();
		return $data["_id"];	
	}

	public function getNext()
	{
		return (is_object($this->Cursor)) ? $this->Cursor->getNext() : false;
	}

	public function hint($field, $order = "ASC")
	{
		if ($order === "ASC") {
			$this->hint[$field] = 1;
		} else {
			$this->hint[$field] = -1;
		}
	}

 	public function insert($collection = null, $data = null, $_id = true)
 	{
 		if ($collection and is_array($data)) {
 			$this->Mongo->selectCollection($this->db["dbNoSQLDatabase"], $collection)->insert($data, $_id);
 			return true;
 		} elseif (is_array($this->data)) {
			$this->Mongo->selectCollection($this->db["dbNoSQLDatabase"], $this->collection)->insert($this->data, $_id);				
			unset($this->data);
			$this->data = array();
			return true;
		} 

		return false;
	}

	public function limit($limit = 1)
	{
		$this->limit = ($limit > 0) ? $limit : false;
	}

	public function operator($field = null, $operator = "<", $value = 0, $json = false)
	{
		if ($operator === "<") {
			return (!$json) ? array($field => array('$lt'  => $value)) : '';
		} elseif ($operator === ">") {
			return (!$json) ? array($field => array('$gt'  => $value)) : '';
		} elseif ($operator === "<=") {
			return (!$json) ? array($field => array('$lte' => $value)) : '';
		} elseif ($operator === ">=") {
			return (!$json) ? array($field => array('$gte' => $value)) : '';
		} elseif ($operator === "!=" or $operator === "<>") {
			return (!$json) ? array($field => array('$ne'  => $value)) : '';
		} elseif ($operator === "in") {
			return (!$json) ? array($field => array('$in'  => $value)) : '';
		} elseif ($operator === "all") {
			return (!$json) ? array($field => array('$all'  => $value)) : '';
		} elseif ($operator === "exists") {
			return (!$json) ? array($field => array('$exists' => $value)) : '';
		} elseif ($operator === "inc" or $operator === "++") {
			return (!$json) ? array($field => array('$inc' => $value)) : '';
		} elseif ($operator === "or" or $operator === "||") {
			if (is_array($field)) {
				return (!$json) ? array('$or' => $field) : '';
			} else {
				return (!$json) ? array('$or' => array($field => $value)) : '';
			}
		} elseif ($operator === "set") {
			return (!$json) ? array('$set' => array($field => $value)) : '';
		} elseif ($operator === "unset") {
			return (!$json) ? array('$unset' => array($field => $value)) : '';
		} elseif ($operator === "push") {
			return (!$json) ? array('$push' => array($field => $value)) : '';
		} elseif ($operator === "pushAll") {
			return (!$json) ? array('$pushAll' => $field) : '';
		} elseif ($operator === "addToSet") {
			if (is_array($field)) {
				return (!$json) ? array('$addToSet' => $field) : '';
			} else {
				return (!$json) ? array('$addToSet' => array($field => $value)) : '';
			}
		} elseif ($operator === "pop") {
			return (!$json) ? array('$pop' => array($field => $value)) : '';
		} 

		return false;
	}
		
	public function regex($regex, $field)
	{
		$Regex = new MongoRegex($regex);	
		return $this->find(array($field => $regex));
	}
		
	public function rows()
	{
		return $this->Cursor->count();
	}

	public function save($option = null, $_id = true)
	{	
		if (is_null($option)) {
			$this->insert($_id);
		} elseif (is_array($option)) {
			$this->Mongo->selectCollection($this->db["dbNoSQLDatabase"], $this->collection)->save($option);
		}
	}
	
	public function set($field, $value)
	{
		$this->data[$field] = $value;
	}
	
	public function skip($skip = 1)
	{
		$this->skip = ($skip > 0) ? $skip : false;
	}

	public function slice($field, $count = 1)
	{
		$this->condition = (object) array($field => array('$slice' => $count));
	}

	public function sort($field, $order = "ASC")
	{
		$this->sort[$field] = ($order === "ASC") ? 1 : -1;
	}
	
	public function update($criteria = false, $update = false, $options = false)
	{	
		if (is_null($this->collection) or !$criteria) {
			return false;
		}
		
		$options = ($options) ? $options : array("upsert" => true);
		
		if (!$update and is_array($this->data)) {
			$update = $this->data;
		}
		
		$this->Mongo->selectCollection($this->db["dbNoSQLDatabase"], $this->collection)->update($criteria, $update, $options);	
		return true;
	}

	public function upload($fname = "file")
	{
		$GridFS = $this->Mongo->selectCollection($this->db["dbNoSQLDatabase"], $this->collection)->getGridFS();	
		$name = FILES($fname, "name");
		return $GridFS->storeUpload($fname, $name);
	}	
}