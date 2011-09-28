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
class ZP_RESTClient extends ZP_Load {
	
	private $auth = FALSE;
	
	public function DELETE($data = FALSE, $return = FALSE) {
		if($data !== TRUE) {
			$data = is_array($data) ? http_build_query($data) : $data;
		
			if(is_null($this->URL) or is_null($data)) {
				return FALSE;
			}
		} else {
			if(is_null($this->URL)) {
				return FALSE;
			}
		}
		
		if($ch = curl_init($this->URL)) {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			
			if($this->auth) {
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
				curl_setopt($ch, CURLOPT_USERPWD, $this->username .":". $this->password);
			}
        
			$response = curl_exec($ch);
			
			$status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
			
			curl_close($ch);
			
			if($status === 200) {
				if($return) {
					return $response;
				}
				
				if(strstr($response, "xml")) {
					return new SimpleXMLElement($response);
				} else {
					return json_decode($response);
				}
			} 
		} 
		
		return FALSE;
	}

	public function GET($return = FALSE) {
		if(is_null($this->URL)) {
			return FALSE;
		}
		
		if($ch = curl_init($this->URL)) {
			curl_setopt($ch, CURLOPT_URL, $this->URL);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
			
			if($this->auth) {
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
				curl_setopt($ch, CURLOPT_USERPWD, $this->username .":". $this->password);
			}
			
			$response = curl_exec($ch);

			$status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
			
			curl_close($ch);
			
			if($status === 200) {
				if($return) {
					return $response;
				}
				
				if(strstr($response, "xml")) {
					return new SimpleXMLElement($response);
				} else {
					return json_decode($response);
				}
			}
		} 
		
		return FALSE;
	}

	public function POST($data = NULL, $return = FALSE) {		
		if(is_null($this->URL) or is_null($data)) {
			return FALSE;
		}
		
		if($ch = curl_init($this->URL)) {
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			
			if($this->auth) {
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
				curl_setopt($ch, CURLOPT_USERPWD, $this->username .":". $this->password);
			}
			
			$response = curl_exec($ch);
			
			$status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
			
			curl_close($ch);
			
			if($status === 200) {
				if($return) {
					return $response;
				}
				
				if(strstr($response, "xml")) {
					return new SimpleXMLElement($response);
				} else { 
					return json_decode($response);
				}
			}
		} 
		
		return FALSE;
	} 
	
	public function PUT($data = NULL, $return = FALSE) {
		$data = is_array($data) ? http_build_query($data) : $data;
		
		if(is_null($this->URL) or is_null($data)) {
			return FALSE;
		}
		
		if($ch = curl_init($this->URL)) {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Length: ". strlen($data)));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			
			if($this->auth) {
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
				curl_setopt($ch, CURLOPT_USERPWD, $this->username .":". $this->password);
			}
			
			$response = curl_exec($ch);

			$status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
			
			curl_close($ch);
			
			if($status === 200) {
				if($return) {
					return $response;
				}
				
				if(strstr($response, "xml")) {
					return new SimpleXMLElement($response);
				} else {
					return json_decode($response);
				}
			}
		} 
		
		return FALSE;
	} 
	
	public function setAuth($username = NULL, $password = NULL) {
		if(!is_null($username) and !is_null($password)) {
			$this->auth	 	= TRUE;
			$this->username = $username;
			$this->password = $password;
		} else {
			$this->auth	= FALSE;
		}
		
		return FALSE;
	}
	
	public function setURL($URL) {
		if(substr($URL, 0, 7) !== "http://") {
			$this->URL = "http://" . $URL;
		} elseif(substr($URL, 0, 8) === "https://") {
			$this->URL = "https://" . $URL;
		} else {
			$this->URL = $URL;
		}
	}
		
}
