<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

/**
 * Website
 */
$ZP["webURL"] 		= "http://127.0.0.1/ZanPHP";
$ZP["webName"] 		= "ZanPHP";
$ZP["webTheme"] 	= "default";
$ZP["webSituation"] = "Active";
$ZP["webMessage"] 	= "";

/**
 * Server
 */
$ZP["production"] = FALSE;
$ZP["domain"] 	  = FALSE;
$ZP["modRewrite"] = FALSE;
$ZP["autoRender"] = TRUE;

/**
 * Applications
 */
$ZP["defaultApplication"] = "default";

/**
 * Languages
 */
$ZP["webLanguage"] = "Spanish";
$ZP["translation"] = "normal";

/**
 * Constants
 */
define("_sh", "/");
define("_index", "index.php");
define("_secretKey", "_eh{Ll&}`<6Y\mg1Qw(;;|C3N9/7*HTpd7SK8t/[}R[vW2)vsPgBLRP2u(C|4]%m_");

if(!$ZP["modRewrite"]) {
	$ZP["webBase"] = $ZP["webURL"] . _sh . _index;
} else {
	$ZP["webBase"] = $ZP["webUrl"];
}