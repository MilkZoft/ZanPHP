<?php
if (!defined("ACCESS")) {
    die("Error: You don't have permission to access here...");
}

class ZP_RESTClient extends ZP_Load
{	
	private $auth = false;

	public function DELETE($data = false, $return = false)
	{
		if ($data !== true) {
			$data = is_array($data) ? http_build_query($data) : $data;
		
			if (is_null($this->URL) or is_null($data)) {
				return false;
			}
		} else {
			if (is_null($this->URL)) {
				return false;
			}
		}
		
		if ($ch = curl_init($this->URL)) {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			
			if ($this->auth) {
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
				curl_setopt($ch, CURLOPT_USERPWD, $this->username .":". $this->password);
			}
        
			$response = curl_exec($ch);
			$status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			
			if ($status === 200) {
				if ($return) {
					return $response;
				}
				
				if (strstr($response, "xml")) {
					return new SimpleXMLElement($response);
				} else {
					return json_decode($response);
				}
			} 
		} 
		
		return false;
	}

	public function GET($return = false)
	{
		if (is_null($this->URL)) {
			return false;
		}

		$ch = curl_init($this->URL);
		
		if ($ch) {
			curl_setopt($ch, CURLOPT_URL, $this->URL);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
			
			if ($this->auth) {
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
				curl_setopt($ch, CURLOPT_USERPWD, $this->username .":". $this->password);
			}
			
			$response = curl_exec($ch);
			$status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			
			if ($status === 200) {
				if ($return) {
					return $response;
				}

				return (strstr($response, "xml")) ? new SimpleXMLElement($response) : json_decode($response, true);				
			}
		} 
		
		return false;
	}

	public function POST($data = null, $return = false)
	{
		if (is_null($this->URL) or is_null($data)) {
			return false;
		}
		
		if ($ch = curl_init($this->URL)) {
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			
			if ($this->auth) {
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
				curl_setopt($ch, CURLOPT_USERPWD, $this->username .":". $this->password);
			}
			
			$response = curl_exec($ch);
			$status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			
			if ($status === 200) {
				if ($return) {
					return $response;
				}
				
				return (strstr($response, "xml")) ? new SimpleXMLElement($response) : json_decode($response, true);
			}
		} 
		
		return false;
	} 
	
	public function PUT($data = null, $return = false)
	{
		$data = is_array($data) ? http_build_query($data) : $data;
		
		if (is_null($this->URL) or is_null($data)) {
			return false;
		}
		
		if ($ch = curl_init($this->URL)) {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Length: ". strlen($data)));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			
			if ($this->auth) {
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
				curl_setopt($ch, CURLOPT_USERPWD, $this->username .":". $this->password);
			}
			
			$response = curl_exec($ch);
			$status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			
			if ($status === 200) {
				if ($return) {
					return $response;
				}

				return (strstr($response, "xml")) ? new SimpleXMLElement($response) : json_decode($response, true);
			}
		} 
		
		return false;
	} 
	
	public function setAuth($username = null, $password = null)
	{
		if (!is_null($username) and !is_null($password)) {
			$this->auth	= true;
			$this->username = $username;
			$this->password = $password;
		} else {
			$this->auth	= false;
		}
		
		return false;
	}
	
	public function setURL($URL)
	{
		$this->URL = $URL;
	}		
}