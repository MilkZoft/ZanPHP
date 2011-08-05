<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

/**
 * Basics
 */
define("_webURL", "http://localhost/ZanPHP");
define("_webName", "ZanPHP");
define("_webTheme", "zanphp");
define("_domain", FALSE);
define("_modRewrite", FALSE);
define("_webCharacters", TRUE);
define("_defaultApplication", "default");
define("_webState", "Active");
define("_webLanguage", "English");
define("_webLang", "en");

/**
 * Cache
 */
define("_cacheDir", "lib/cache");
define("_cacheTime", 3600);
define("_cacheExt", ".cache");
define("_cacheStatus", TRUE);

/**
 * Languages:
 */
define("_Spanish", TRUE);
define("_English", TRUE);
define("_French", FALSE);
define("_Portuguese", FALSE);
define("_flags", "flags");
define("_language", "language");
define("_languages", "languages");

/**
 * E-Mail
 */
define("_gUser", "youremail@gmail.com");
define("_gPwd", "USER PASSWORD");
define("_gSSL", "ssl://smtp.gmail.com");
define("_gPort", 465);
 
/**
 * Credits
 */
define("_ZanPHP", "ZanPHP");

/**
 * Helpers & Hooks:
 */
define("_helpers", "helpers");
define("_helper", "helper");
define("_hooks", "hooks");
define("_hook", "hook");

/**
 * Images:
 */
define("_library", "library");
define("_image", "image");
define("_images", "images");
define("_maxWidth", 720);
define("_maxHeight", 380);
define("_minSmall", 60);
define("_maxSmall", 90);
define("_minMini", 90);
define("_maxMini", 90);
define("_minMedium", 220);
define("_maxMedium", 320);
define("_minOriginal", 560);
define("_maxOriginal", 800);
define("_fileSize", 10485760);

/**
 * Pagination:
 */
define("_page", "page");
define("_top", "#top");

/**
 * Security:
 */
define("_secretKey", "_eh{Ll&}`<6Y\mg1Qw(;;|C3N9/7*HTpd7SK8t/[}R[vW2)vsPgBLRP2u(C|4]%m_");
define("_super", "Super Admin");

/**
 * Themes & Templates:
 */
define("_views", "views");
define("_view", "view");
define("_themes", "themes");
define("_scripts", "scripts");
define("_AJAX", "ajax");

/**
 * Web Configuration & Directories:
 */
define("_applications", "applications");
define("_core", "core");
define("_config", "config");
define("_CSS", "css");
define("_class", "class");
define("_classes", "classes");
define("_controller", "controller");
define("_controllers", "controllers");
define("_model", "model");
define("_models", "models");
define("_favicon", "favicon.ico");
define("_guest", "guest");
define("_index", "index.php");
define("_icons", "icons");
define("_files", "files");
define("_js", "js");
define("_lib", "lib");
define("_libraries", "libraries");
define("_URL", "URL");
define("_sh", "/");
define("_dot", ".");
define("_PHP", ".php");

if(!_modRewrite) {
	define("_webBase", _webURL . _sh . _index);
} else {
	define("_webBase", _webURL);
}