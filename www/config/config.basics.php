<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

/**
 * General
 */
define("_webURL", "http://localhost/ZanPHP");
define("_webName", "ZanPHP");
define("_webTheme", "zanphp");
define("_webSituation", "Active");
define("_webMessage", "Under construction");
define("_webLanguage", "Spanish");
define("_webLang", "es");

define("_domain", FALSE);
define("_modRewrite", FALSE);
define("_defaultApplication", "default");
define("_translation", "normal");
define("_sh", "/");
define("_dot", ".");
define("_PHP", ".php");
define("_controller", "controller");
define("_model", "model");
define("_favicon", "favicon.ico");
define("_guest", "guest");
define("_index", "index.php");
define("_URL", "URL");

/**
 * Paths
 */
define("_www", "www");
define("_controllers", "controllers");
define("_models", "models");
define("_applications", "applications");
define("_config", "config");
define("_CSS", "css");
define("_class", "class");
define("_classes", "classes");
define("_icons", "icons");
define("_files", "files");
define("_js", "js");
define("_lib", "lib");
define("_libraries", "libraries");
define("_helpers", "helpers");
define("_helper", "helper");
define("_hooks", "hooks");
define("_hook", "hook");
define("_drivers", "drivers");
define("_driver", "driver");

/**
 * Credits
 */
define("_ZanPHP", "ZanPHP");

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

if(!_modRewrite) {
	define("_webBase", _webURL . _sh . _index);
} else {
	define("_webBase", _webURL);
}
