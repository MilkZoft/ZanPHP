<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

if (!function_exists("getFacebookLogin")) {
	function getFacebookLogin()
	{
		SESSION("state", code(32));	
		redirect("https://www.facebook.com/dialog/oauth?client_id=". FB_APP_ID ."&redirect_uri=". encode(FB_APP_URL, true) ."&state=". SESSION("state") ."&scope=". FB_APP_SCOPE);						
	}
}

if (!function_exists("isConnectedToFacebook")) {
	function isConnectedToFacebook()
	{
		if (SESSION("state") and SESSION("state") === REQUEST("state")) {
			return true;
		}

		return false;
	}
}

if (!function_exists("getFacebookUser")) {
	function getFacebookUser($code)
	{
	 	$response = file_get_contents("https://graph.facebook.com/oauth/access_token?client_id=". FB_APP_ID ."&redirect_uri=". encode(FB_APP_URL, true) ."&client_secret=". FB_APP_SECRET ."&code=". $code);
	 	$params = null;
	 	parse_str($response, $params);

	 	if (isset($params["access_token"])) {
	 		SESSION("ZanUserServiceAccessToken", $params["access_token"]);
	 		$graphURL = "https://graph.facebook.com/me?fields=". FB_APP_FIELDS ."&access_token=". $params["access_token"];
	 		$user = json_decode(file_get_contents($graphURL));

			return array(
		 		"serviceID" => $user->id,
		 		"username"  => $user->username,
				"name" 		=> $user->name,
				"email" 	=> $user->email,
				"birthday"  => $user->birthday,
				"avatar"	=> $user->picture->data->url
			);
	 	}

	 	return false;
	}
}