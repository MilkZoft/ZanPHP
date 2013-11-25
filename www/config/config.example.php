<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

/**
 * Website
 */
$ZP["webURL"] = "http://localhost/ZanPHP";
$ZP["webName"] = "ZanPHP";
$ZP["webTheme"] = "default";
$ZP["webSituation"] = "Active";
$ZP["webMessage"] = "";
$ZP["benchMark"] = false;

/**
 * Server
 *
 * Environment: 
 *  1. Development 
 *  2. Demo 
 *  3. Stage  
 *  4. Production
 */
$ZP["environment"]  = 1;
$ZP["optimization"] = true;
$ZP["domain"] = false;
$ZP["modRewrite"] = false;
$ZP["autoRender"] = true;
$ZP["allowIP"] = array("127.0.0.1");

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
define("SH", "/");
define("CORE_PATH", "zan");
define("INDEX", "index.php");
define("SECRET_KEY", "_eh{Ll&}`<6Y\mg1Qw(;;|C3N9/7*HTpd7SK8t/[}R[vW2)vsPgBLRP2u(C|4]%m_");
define("DEFAULT_TIMEZONE", "America/Mexico_City");
define("VIA", "codejobs");

/**
 * Twitter App
 */
define("TW_CONSUMER_KEY", "Your Twitter Consumer Key");
define("TW_CONSUMER_SECRET", "Your Twitter Consumer Secret");
define("TW_REQUEST_TOKEN_URL", "http://twitter.com/oauth/request_token");
define("TW_AUTHORIZE_URL", "http://twitter.com/oauth/authorize");
define("TW_ACCESS_TOKEN_URL", "http://twitter.com/oauth/access_token");

/**
 * Facebook App
 */
define("FB_APP_ID", "Your Facebook App ID");
define("FB_APP_SECRET", "Your Facebook App Secret");
define("FB_APP_SCOPE", "email,user_birthday,read_stream");
define("FB_APP_FIELDS", "id,name,email,birthday,picture,username");
define("FB_APP_URL", "Your Facebook App URL");

/**
 * Google Adsense
 */                  
define("AD_CLIENT", "");
define("AD_SLOT_BLOCK", "");
define("AD_WIDTH_BLOCK", "300");
define("AD_HEIGHT_BLOCK", "250");
define("AD_SLOT_SKY", "");
define("AD_WIDTH_SKY", "728");
define("AD_HEIGHT_SKY", "90");
define("AD_SLOT_MEDIUM", "");
define("AD_WIDTH_MEDIUM", "234");
define("AD_HEIGHT_MEDIUM", "60");


/**
 * Cache
 */
define("CACHE_STATUS", false);
define("CACHE_DRIVER", "File");
define("CACHE_HOST", "localhost"); 
define("CACHE_PORT", "11211");
define("CACHE_DIR", "www/lib/cache");
define("CACHE_TIME", 3600);
define("CACHE_EXT", ".cache");

/**
 * E-Mail
 */
define("GMAIL_USER", "youremail@gmail.com");
define("GMAIL_PWD", "USER PASSWORD");
define("GMAIL_SSL", "ssl://smtp.gmail.com");
define("GMAIL_PORT", 465);

/**
 * Images:
 */
define("MAX_WIDTH", 720);
define("MAX_HEIGHT", 380);
define("MIN_SMALL", 60);
define("MAX_SMALL", 90);
define("MIN_THUMBNAIL", 90);
define("MAX_THUMBNAIL", 90);
define("MIN_MEDIUM", 220);
define("MAX_MEDIUM", 320);
define("MIN_LARGE", 700);
define("MAX_LARGE", 1024);
define("FILE_SIZE", 10485760);

$ZP["webBase"] = (!$ZP["modRewrite"]) ? $ZP["webURL"] . SH . INDEX : $ZP["webURL"];
