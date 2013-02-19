<?php
if (!defined("ACCESS")) {
    die("Error: You don't have permission to access here...");
}

class ZP_RESTServer extends ZP_Load
{
	private $data = array();
	
	public function application($application = null)
	{
		$this->application = $application;
	}
	
	public function authenticate($application)
	{
		$this->config($application);
		
		if (!defined("REST_AUTH") or !defined("REST_USER") or !defined("REST_PWD")) {
			return false;
		}
		
		if (REST_AUTH) {
			$user[REST_USER] = REST_PWD;
			
			if (empty($_SERVER["PHP_AUTH_DIGEST"])) {
				header("HTTP/1.1 401 Unauthorized");
				header("WWW-Authenticate: Digest realm=\"Restricted area\", qop=\"auth\", nonce=\"". uniqid() . "\", opaque=\"". md5($realm) ."\"");
			}
			
			$data = parseHTTP($_SERVER["PHP_AUTH_DIGEST"]);
			
			if (!$data or !isset($user[$data["username"]])) {
				return false;
			}

			$a1 = md5($data["username"] .":Restricted area:". $user[$data["username"]]);
			$a2 = md5($_SERVER["REQUEST_METHOD"] .":". $data["uri"]);
			$response = md5($a1 .":". $data["nonce"] .":". $data["nc"] .":". $data["cnonce"] .":". $data["qop"] .":". $a2);

			if ($data["response"] !== $response) {
				return false;
			}
			
			return true;	
		} else {
			return true;
		}
	}
	
	public function data()
	{
		if ($this->method() === "PUT") {
			parse_str(file_get_contents("php://input"), $data);
		} elseif ($this->method() === "POST" or $this->method() === "DELETE") {
			$data = POST(true);
		}
		
		return isset($data) ? $data : false;
	}
	
	public function isREST($segment = 0, $method = "get")
	{
		if ($method === "get" or $method === "post" or $method === "delete" or $method === "put") {
			if (isLang() and segment($segment) === $method or !isLang() and segment($segment - 1) === $method) {
				if ($this->authenticate($this->application)) {
					return true;
				}
			}
		} elseif ($segment === true and $this->method() === $method) {
			if ($this->authenticate($this->application)) {
				return true;
			}
		} elseif ($this->method() === "GET" or $this->method() === "POST" or $this->method() === "DELETE" or $this->method() === "PUT") {
			if ($this->authenticate($this->application)) {	
				return true;
			}
		}
		
		return false;
	}
	
	public function message($message, $error = false)
	{
		return array("error" => $error, "message" => __($message, true));
	}
	
	public function method()
	{
		return $_SERVER["REQUEST_METHOD"];
	}
	
	public function process($data, $segment = 4)
	{
		if ($this->authenticate($this->application)) {
			$this->response($data, $segment);
		} else {
			return false;
		}
	}
	
	public function response($data, $segment = 4, $method = "GET")
	{
		if ($this->method() === $method) { 
			if (isLang() and segment($segment) === "xml" or !isLang() and segment($segment - 1) === "xml") {
				header("Content-Type: text/xml"); 			
				$this->Array2XML = $this->core("Array2XML");
				$this->Array2XML->printXML($data, "data", "contact");			
			} else {
				print json_encode($data);
			}
		} else {
			return false;
		}
	}
}