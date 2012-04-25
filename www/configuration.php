<?php 
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

set("webLang", whichLanguage(FALSE));

if(get("translation") === "gettext") {
	$languageFile = _dir ."/lib/languages/gettext/". whichLanguage(TRUE, TRUE) .".mo";
		
	if(file_exists($languageFile)) { 			
		$Load->library("streams", NULL, NULL, "gettext");

		$Gettext_Reader = $Load->library("gettext", "Gettext_Reader", array($languageFile), "gettext");
	
		$Gettext_Reader->load_tables();
	}
}