<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

if (!function_exists("getTwitterUser")) {
	function getTwitterUser($oauthToken, $Twitter)
	{
		$Twitter->setToken($oauthToken);
		$accessToken = $Twitter->getAccessToken();
		$Twitter->setToken($accessToken->oauth_token, $accessToken->oauth_token_secret);	  	

		SESSION("ZanUserServiceAccessToken", $accessToken->oauth_token);
		SESSION("ZanUserServiceAccessTokenSecret", $accessToken->oauth_token_secret);
		
		$data = $Twitter->get_accountVerify_credentials();
		return array("service" => "twitter", "serviceID" => $data->id, "username" => $data->screen_name, "name"	=> $data->name, "email" => null, "birthday" => null, "avatar" => $data->profile_image_url_https);
	}
}

if (!function_exists("getTwitterLogin")) {
	function getTwitterLogin()
	{
		global $Load;

		$Twitter = $Load->library("twitter", "EpiTwitter", array(TW_CONSUMER_KEY, TW_CONSUMER_SECRET));
		
		redirect($Twitter->getAuthenticateUrl());		
	}
}

if (!function_exists("setTwitterToken")) {
	function setTwitterToken($token = null, $secret = null)
	{
		global $Load;
		
		$Twitter = $Load->library("twitter", "EpiTwitter", array(TW_CONSUMER_KEY, TW_CONSUMER_SECRET));
		
		$Twitter->setToken($token);
	}
}

if (!function_exists("getTwitterAccessToken")) {
	function getTwitterAccessToken()
	{
		global $Load;
		
		$Twitter = $Load->library("twitter", "EpiTwitter", array(TW_CONSUMER_KEY, TW_CONSUMER_SECRET));
		
		return $Twitter->getAccessToken();
	}
}

if (!function_exists("getTwitterCredentials")) {
	function getTwitterCredentials()
	{
		global $Load;
		
		$Twitter = $Load->library("twitter", "EpiTwitter", array(TW_CONSUMER_KEY, TW_CONSUMER_SECRET));
		
		return $Twitter->get("/account/verify_credentials.json"); 
	}
}