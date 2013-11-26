<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

if (!function_exists("code")) {
	function code($max = 10, $uppercase = true) 
	{	
		return ($uppercase) ? strtoupper(substr(md5(date("Y-m-d H:i:s", time())), 0, $max)) : substr(md5(date("Y-m-d H:i:s", time())), 0, $max);
	}
}

if (!function_exists("encrypt")) {
	function encrypt($password = null, $strong = 3, $key = true, $uppercase = false)
	{
		$password = (!$key) ? $password . substr(md5(date("Y-m-d H:i:s", time())), 0, 10) : "ZanPHP" . SECRET_KEY . $password;
		
		if ($strong === 1) {
			$hash = md5(md5(md5($password)));
		} elseif ($strong === 2) {
			$hash = sha1(sha1(sha1($password)));
		} else {
			$hash = sha1(md5(sha1(md5(sha1(md5($password))))));		
		}
		
		return ($uppercase) ? strtoupper($hash) : $hash;
	}
}

if (!function_exists("getIP")) {
	function getIP()
	{
		return isset($_SERVER["HTTP_CLIENT_IP"]) ? $_SERVER["HTTP_CLIENT_IP"] : $_SERVER["REMOTE_ADDR"];
	}
}

if (!function_exists("isAllowedIP")) {
	function isAllowedIP()
	{
		if (in_array(getIP(), _get("allowIP"))) {
			return true;
		}	

		return false;
	}
}

if (!function_exists("parseHTTP")) {
	function parseHTTP($string)
	{
	    $parts = array("nonce" => true, "nc" => true, "cnonce" => true, "qop" => true, "username" => true, "uri" => true, "response" => true);	    
	    $keys = implode("|", array_keys($parts));
	    $data = array();
		
	    preg_match_all('@('. $keys .')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $string, $matches, PREG_SET_ORDER);
		
	    foreach ($matches as $match) {
	        $data[$match[1]] = $match[3] ? $match[3] : $match[4];	        
	        unset($parts[$match[1]]);
	    }
		
	    return (count($parts) === 0) ? $data : false;
	}
}