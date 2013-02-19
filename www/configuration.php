<?php 
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

date_default_timezone_set(DEFAULT_TIMEZONE);

set("webLang", whichLanguage(false));

if (_get("translation") === "gettext") {
	$languageFile = DIR ."/lib/languages/gettext/". whichLanguage(true, true) .".mo";
	
	if (file_exists($languageFile)) { 			
		$Load->library("streams", null, null, "gettext");
		$Gettext_Reader = $Load->library("gettext", "Gettext_Reader", array($languageFile), "gettext");
		$Gettext_Reader->load_tables();
	}
}