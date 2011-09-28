<?php 
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
class ZP_MongoDB extends ZP_Load {
		
	private $collection = NULL;

	private $condition = FALSE;

	/**
	 * 
	 * 
	 * @var private static $connection = FALSE
	 */
	private static $connection = FALSE;

	private $data = FALSE;
	
	/**
	 * Contains the fields of the table
	 * 
	 * @var private $fields
	 */
	private $fields;
	
	private $hint = FALSE;

	public $json = NULL;
	
	private $limit = FALSE;
	
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
	
	private $skip = FALSE;
	
	private $sort = array("_id" => 1);
	
	/**
	 * Contains the values of the query
	 * 
	 * @var private $values
	 */
	private $values;

    /**
     * Load Database class
     *
     * @return void
     */
	public function __construct() {		
		$this->helper("debugging");
		
		$this->config("database");
		$this->connect();	
	}

	public function collection($collection = NULL) {
		if(!is_null($collection)) {
			$this->collection = $collection;
		}
	}

	public function command($command) {
		$this->Mongo->selectCollection(_dbNoSQLDatabase, $this->collection)->command($command);
	}
	
    /**
     * Select database driver and make connection
     *
     * @return void
     */
	public function connect() {
		if(!self::$connection) {
			try {
				$this->Mongo = new Mongo("mongodb://". _dbNoSQLHost .":". _dbNoSQLPort);
				
				if(!$this->Mongo) {
					throw new Exception(e("Connection Error"), 1);
				}
				
				if(_dbNoSQLUser !== "" and _dbNoSQLPwd !== "") {
					$this->Mongo->selectDb(_dbNoSQLDatabase)->authenticate(_dbNoSQLUser, _dbNoSQLPwd);
				} else {
					$this->Mongo->selectDb(_dbNoSQLDatabase);
				}
			} catch(Exception $e) {
				getException($e);	
			}
		}									
	}
		
    /**
     * Count all records
     *
     * @return integer value
     */	
	public function countAll() {				
		if(is_object($this->Cursor)) {
			return $this->Cursor->count();
		} else {
			if($this->collection) {
		 		$this->find();
		 		
		 		return $this->Cursor->count();	
			} else {
				return FALSE;
			}
		} 
	}

    /**
     * Count records by SQL query
     *
     * @return integer value
     */
	public function countByQuery($query) {		
		$this->find($query, FALSE);
		
		return (is_object($this->Cursor)) ? $this->Cursor->count() : FALSE;
	}

	private function data() {			
		if($this->Cursor->count() === 0) {
			return FALSE;			
		} else {
			$this->Cursor->sort($this->sort);
			
			if($this->limit > 0) {
				$this->Cursor->limit($this->limit);
				
				$this->limit = FALSE;
			}
			
			if($this->skip > 0) {
				$this->Cursor->skip($this->skip);
			}
			
			if(is_array($this->hint)) {
				$this->Cursor->hint($this->hint);
			}
			
			$data = iterator_to_array($this->Cursor, FALSE);
		}	

		return $data;
	}

	public function delete($criteria, $justOne = TRUE, $safe = TRUE) {	
		if(is_null($this->collection) or !$criteria) {
			return FALSE;
		}
		
		if($justOne and $safe) {
			$options = array("justOne" => TRUE, "safe" => TRUE);
		} elseif($justOne) {
			$options = array("justOne" => TRUE);
		} elseif($safe) {
			$options = array("safe" => TRUE);
		}
	
		$this->Mongo->selectCollection(_dbNoSQLDatabase, $this->collection)->remove($criteria, $options);
		
		return TRUE;
	}
	
	public function deleteBy($field = FALSE, $value = FALSE, $justOne = TRUE, $safe = TRUE) {
		$criteria = array($field => $value);
		
		return $this->delete($criteria, $justOne, $safe);
	}

	public function deleteFile($_id) {
		$GridFS = $this->Mongo->selectCollection(_dbNoSQLDatabase, $this->collection)->getGridFS();

		$ID = new MongoId($_id);
		
		$GridFS->delete($ID);
		
		return TRUE;
	}
	
	public function drop($collection = NULL) {
		if($collection) {
			$this->Mongo->selectCollection(_dbNoSQLDatabase, $collection)->drop();
		} else {
			$this->Mongo->selectCollection(_dbNoSQLDatabase, $this->collection)->drop();	
		}
		
		return TRUE;
	}

	public function ensureIndex($index = FALSE, $order = "ASC", $unique = FALSE) {
		if($index and $order === "ASC" and $unique) {
			$this->Mongo->ensureIndex(array($index => 1), array("unique" => TRUE));
		} elseif($index and $order === "DESC" and $unique) {
			$this->Mongo->ensureIndex(array($index => -1), array("unique" => TRUE));
		} elseif($index and $order === "ASC") {
			$this->Mongo->ensureIndex(array($index => 1));
		} elseif($index and $order === "DESC") {
			$this->Mongo->ensureIndex(array($index => -1));
		}
		
		return FALSE;
	}

	public function find($query = NULL, $return = TRUE) {
		if(is_null($query)) {
			$this->Cursor = $this->Mongo->selectCollection(_dbNoSQLDatabase, $this->collection)->find();
		} else { 
			if(!is_array($query) and is_string($query)) {
				$query = json_decode($query, TRUE);
			}
			
			if($this->condition) {
				$this->Cursor = $this->Mongo->selectCollection(_dbNoSQLDatabase, $this->collection)->find($query, $this->condition);
				
				unset($this->condition);
				
				$this->condition = FALSE;
			}
			
			$this->Cursor = $this->Mongo->selectCollection(_dbNoSQLDatabase, $this->collection)->find($query);
		}
		
		return $this->data();
	}
	
    /**
     * Find all records
     *
     * @param string $group = NULL
     * @param string $order = NULL
     * @param string $limit = NULL
     * @return array value
     */
	public function findAll($collection = NULL, $group = NULL, $order = NULL, $limit = NULL) {
		if($collection) {
			$this->collection = $collection;	
		}

		if(is_null($this->collection)) {
			return FALSE;
		}
		
		$this->Cursor = $this->Mongo->selectCollection(_dbNoSQLDatabase, $this->collection)->find();
	
		return $this->data();		
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
	public function findBy($field, $value, $collection = NULL) {
		if($collection) {
			$this->collection = $collection;	
		}

		if(is_null($this->collection)) {
			return FALSE;
		}
		
		$query = array($field => $value);
		
		$this->Cursor = $this->Mongo->selectCollection(_dbNoSQLDatabase, $this->collection)->find($query);
	
		return $this->data();
	}

    /**
     * Find record by primary key
     *
     * @param integer $ID
     * @return boolean value / array value
     */
	public function findByID($ID, $collection = NULL) {
		if($collection) {
			$this->collection = $collection;	
		}

		if(is_null($this->collection)) {
			return FALSE;
		}
		
		if(strlen($ID) === 24) {
			$ID = new MongoId($ID);
		}
		
		$query = array("_id" => $ID);
		
		$this->Cursor = $this->Mongo->selectCollection(_dbNoSQLDatabase, $this->collection)->find($query);
	
		return $this->data();
	}
	
    /**
     * Find the first record
     *
     * @return array value
     */
	public function findFirst($collection = NULL) {
		if($collection) {
			$this->collection = $collection;	
		}

		if(is_null($this->collection)) {
			return FALSE;
		}
		
		$this->Cursor = $this->Mongo->selectCollection(_dbNoSQLDatabase, $this->collection)->findOne();
	
		return $this->data();
	}
		
    /**
     * Find the last record
     *
     * @return array value
     */
	public function findLast($collection = NULL) {
		if($collection) {
			$this->collection = $collection;	
		}

		if(is_null($this->collection)) {
			return FALSE;
		}
				
		$this->Cursor = $this->Mongo->selectCollection(_dbNoSQLDatabase, $this->collection)->find();
		
		$this->sort("_id", "DESC");
		
		$this->limit(1);
		
		return $this->data();
	}

	public function get($type = "AND", $array, $return = FALSE, $add = TRUE) {
		if($add and !$return) {
			if($type === "AND") {
				foreach($array as $field => $value) {
					$this->json .= '"'. $field .'" : "'. $value .'", ';
				}
			} elseif($type === "OR") {
				$keys = array_keys($array);
				
				$j  = 0;
				$or = FALSE;
				
				foreach($array as $values) {
					if(is_array($values)) {
						for($i = 0; $i <= count($values) - 1; $i++) {
							if(!$or) {
								$last = substr($this->json, strlen($this->json) - 1, strlen($this->json));
								
								if($last === "]") {
									$this->json .= ', "$or":[{';
								} else {
									$this->json .= '"$or":[{';
								}
								
								$or = TRUE;
							}
							
							if($j === count($keys) - 1) {
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
			if($type === "AND") {
				$i = 0;
				
				foreach($array as $field => $value) {
					if($i === count($array) - 1) {
						$last = substr($this->json, strlen($this->json) - 1, strlen($this->json));
						
						if($last === "]") {
							$this->json .= ', "'. $field .'" : "'. $value .'"';
						} else {
							$this->json .= '"'. $field .'" : "'. $value .'"';
						} 
					} else {
						$last = substr($this->json, strlen($this->json) - 1, strlen($this->json));
						
						if($last === "]") {
							$this->json .= ', "'. $field .'" : "'. $value .'", ';
						} else {
							$this->json .= '"'. $field .'" : "'. $value .'", ';
						}
					}
				}
			} elseif($type === "OR") {
				$keys = array_keys($array);
				
				$j  = 0;
				$or = FALSE;
				
				foreach($array as $values) {
					if(is_array($values)) {
						for($i = 0; $i <= count($values) - 1; $i++) {
							if(!$or) {
								$last = substr($this->json, strlen($this->json) - 1, strlen($this->json));
								
								if($last === "]") {
									$this->json .= ', "$or":[{';
								} else {
									$this->json .= '"$or":[{';
								}
								
								$or = TRUE;
							}
							
							if($j === count($keys) - 1) {
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
		
		if($return) {
			$return = $this->json;
			
			empty($this->json);
			
			return "{" . $return . "}";
		}
	}

	public function getAllFiles($collection = NULL) {
		if($collection) {
			$this->collection = $collection;	
		}

		if(is_null($this->collection)) {
			return FALSE;
		}

		$GridFS = $this->Mongo->selectCollection(_dbNoSQLDatabase, $this->collection)->getGridFS();

		$Cursor = $GridFS->find();
		
		$i = 0;
		
		foreach($Cursor as $Object) {
			$files[$i]["filename"] = $Object->getFilename();
			$files[$i]["content"]  = $Object->getBytes();
			
			$i++;
		}
		
		return $files;
	}
	
	public function getFile($_id, $collection = NULL, $mimeType = "image/jpeg", $return = FALSE) {
		if($collection) {
			$this->collection = $collection;	
		}

		if(is_null($this->collection)) {
			return FALSE;
		}

		$GridFS = $this->Mongo->selectCollection(_dbNoSQLDatabase, $this->collection)->getGridFS();

		$ID = new MongoId($_id);
		
		$file = $GridFS->findOne(array("_id" => $ID));
		
		if($return) {
			return $file;
		} else {
			header("Content-Type: $mimeType");
			
			print $file->getBytes();
			
			exit;	
		}	
	}

	public function getLastID($collection = NULL) {
		if($collection) {
			$this->collection = $collection;	
		}

		if(is_null($this->collection)) {
			return FALSE;
		}

		$this->Cursor = $this->Mongo->selectCollection(_dbNoSQLDatabase, $this->collection)->find();
		
		$this->sort("_id", "DESC");
		
		$this->limit(1);
		
		$data = $this->data();
		
		return $data["_id"];	
	}

	public function getNext() {
		return (is_object($this->Cursor)) ? $this->Cursor->getNext() : FALSE;
	}

	public function hint($field, $order = "ASC") {
		if($order === "ASC") {
			$this->hint[$field] = 1;
		} else {
			$this->hint[$field] = -1;
		}
	}

 	public function insert($collection = NULL, $data = NULL, $_id = TRUE) {
 		if($collection and is_array($data)) {
 			$this->Mongo->selectCollection(_dbNoSQLDatabase, $collection)->insert($data, $_id);

 			return TRUE;
 		} elseif(is_array($this->data)) {
			$this->Mongo->selectCollection(_dbNoSQLDatabase, $this->collection)->insert($this->data, $_id);
				
			unset($this->data);
				
			$this->data = array();

			return TRUE;
		} 

		return FALSE;
	}

	public function limit($limit = 1) {
		$this->limit = ($limit > 0) ? $limit : FALSE;
	}

	public function operator($field = NULL, $operator = "<", $value = 0, $json = FALSE) {
		if($operator === "<") {
			return (!$json) ? array($field => array('$lt'  => $value)) : '';
		} elseif($operator === ">") {
			return (!$json) ? array($field => array('$gt'  => $value)) : '';
		} elseif($operator === "<=") {
			return (!$json) ? array($field => array('$lte' => $value)) : '';
		} elseif($operator === ">=") {
			return (!$json) ? array($field => array('$gte' => $value)) : '';
		} elseif($operator === "!=" or $operator === "<>") {
			return (!$json) ? array($field => array('$ne'  => $value)) : '';
		} elseif($operator === "in") {
			return (!$json) ? array($field => array('$in'  => $value)) : '';
		} elseif($operator === "all") {
			return (!$json) ? array($field => array('$all'  => $value)) : '';
		} elseif($operator === "exists") {
			return (!$json) ? array($field => array('$exists' => $value)) : '';
		} elseif($operator === "inc" or $operator === "++") {
			return (!$json) ? array($field => array('$inc' => $value)) : '';
		} elseif($operator === "or" or $operator === "||") {
			if(is_array($field)) {
				return (!$json) ? array('$or' => $field) : '';
			} else {
				return (!$json) ? array('$or' => array($field => $value)) : '';
			}
		} elseif($operator === "set") {
			return (!$json) ? array('$set' => array($field => $value)) : '';
		} elseif($operator === "unset") {
			return (!$json) ? array('$unset' => array($field => $value)) : '';
		} elseif($operator === "push") {
			return (!$json) ? array('$push' => array($field => $value)) : '';
		} elseif($operator === "pushAll") {
			return (!$json) ? array('$pushAll' => $field) : '';
		} elseif($operator === "addToSet") {
			if(is_array($field)) {
				return (!$json) ? array('$addToSet' => $field) : '';
			} else {
				return (!$json) ? array('$addToSet' => array($field => $value)) : '';
			}
		} elseif($operator === "pop") {
			return (!$json) ? array('$pop' => array($field => $value)) : '';
		} 

		return FALSE;
	}
		
	public function regex($regex, $field) {
		$Regex = new MongoRegex($regex);
		
		return $this->find(array($field => $regex));
	}
		
	public function rows() {
		return $this->Cursor->count();
	}

	public function save($option = NULL, $_id = TRUE) {	
		if(is_null($option)) {
			$this->insert($_id);
		} elseif(is_array($option)) {
			$this->Mongo->selectCollection(_dbNoSQLDatabase, $this->collection)->save($option);
		}
	}
	
	public function set($field, $value) {
		$this->data[$field] = $value;
	}
	
	public function skip($skip = 1) {
		$this->skip = ($skip > 0) ? $skip : FALSE;
	}

	public function slice($field, $count = 1) {
		$this->condition = (object) array($field => array('$slice' => $count));
	}

	public function sort($field, $order = "ASC") {
		$this->sort[$field] = ($order === "ASC") ? 1 : -1;
	}
	
	public function update($criteria = FALSE, $update = FALSE, $options = FALSE) {	
		if(is_null($this->collection) or !$criteria) {
			return FALSE;
		}
		
		$options = ($options) ? $options : array("upsert" => TRUE);
		
		if(!$update and is_array($this->data)) {
			$update = $this->data;
		}
		
		$this->Mongo->selectCollection(_dbNoSQLDatabase, $this->collection)->update($criteria, $update, $options);
		
		return TRUE;
	}

	public function upload($fname = "file") {
		$GridFS = $this->Mongo->selectCollection(_dbNoSQLDatabase, $this->collection)->getGridFS();
		
		$name = FILES($fname, "name");
		
		return $GridFS->storeUpload($fname, $name);
	}
	
}
