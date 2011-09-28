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
 * ZanPHP Email Class
 *
 * This class allows to manipulate emails
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	classes
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/classes/email_class
 */
class ZP_RESTServer extends ZP_Load {
	
	private $data = array();
	
	public function application($application = NULL) {
		$this->application = $application;
	}
	
	public function authenticate($application) {
		$this->config($application);
		
		if(!defined("_RESTAuth") or !defined("_RESTUser") or !defined("_RESTPwd")) {
			return FALSE;
		}
		
		if(_RESTAuth) {
			$user[_RESTUser] = _RESTPwd;
			
			if(empty($_SERVER["PHP_AUTH_DIGEST"])) {
				header("HTTP/1.1 401 Unauthorized");
				header("WWW-Authenticate: Digest realm=\"Restricted area\", qop=\"auth\", nonce=\"". uniqid() . "\", opaque=\"". md5($realm) ."\"");
			}
			
			$data = parseHTTP($_SERVER["PHP_AUTH_DIGEST"]);
			
			if(!$data or !isset($user[$data["username"]])) {
				return FALSE;
			}

			$a1       = md5($data["username"] .":Restricted area:". $user[$data["username"]]);
			$a2 	  = md5($_SERVER["REQUEST_METHOD"] .":". $data["uri"]);
			$response = md5($a1 .":". $data["nonce"] .":". $data["nc"] .":". $data["cnonce"] .":". $data["qop"] .":". $a2);

			if($data["response"] !== $response) {
				return FALSE;
			}
			
			return TRUE;	
		} else {
			return TRUE;
		}
	}
	
	public function data() {
		if($this->method() === "PUT") {
			parse_str(file_get_contents("php://input"), $data);
		} elseif($this->method() === "POST" or $this->method() === "DELETE") {
			$data = POST(TRUE);
		}
		
		return isset($data) ? $data : FALSE;
	}
	
	public function isREST($segment = 0, $method = "get") {
		if($method === "get" or $method === "post" or $method === "delete" or $method === "put") {
			if(isLang() and segment($segment) === $method or !isLang() and segment($segment - 1) === $method) {
				if($this->authenticate($this->application)) {
					return TRUE;
				}
			}
		} elseif($segment === TRUE and $this->method() === $method) {
			if($this->authenticate($this->application)) {
				return TRUE;
			}
		} elseif($this->method() === "GET" or $this->method() === "POST" or $this->method() === "DELETE" or $this->method() === "PUT") {
			if($this->authenticate($this->application)) {	
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
	public function message($message, $error = FALSE) {
		return array("error" => $error, "message" => __($message, TRUE));
	}
	
	public function method() {
		return $_SERVER["REQUEST_METHOD"];
	}
	
	public function process($data, $segment = 4) {
		if($this->authenticate($this->application)) {
			$this->response($data, $segment);
		} else {
			return FALSE;
		}
	}
	
	public function response($data, $segment = 4, $method = "GET") {
		if($this->method() === $method) { 
			if(isLang() and segment($segment) === "xml" or !isLang() and segment($segment - 1) === "xml") {
				header("Content-Type: text/xml"); 
				
				$this->Array2XML = $this->core("Array2XML");
				
				$this->Array2XML->printXML($data, "data", "contact");			
			} else {
				print json_encode($data);
			}
		} else {
			return FALSE;
		}
	}

}
