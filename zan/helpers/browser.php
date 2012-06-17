<?php
/**
 * ZanPHP
 *
 * An open source agile and rapid development framework for PHP 5
 *
 * @package		ZanPHP
 * @author		MilkZoft Developer Team
 * @copyright	Copyright (c) 2011, MilkZoft, Inc.
 * @license		http://www.zanphp.com/documentation/en/license/
 * @link		http://www.zanphp.com
 * @version		1.0
 */
 
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

/**
 * Browser Helper
 *
 * The Helper Browser allows to detect the User's Browser
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	helpers
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/helpers/browser_helper
 */

/**
 * Get the User Browser String provided by Browser's User Agent
 *
 * @return string
 */
function browser() {
	$browsers = array(
		"Opera" 			  => "(Opera)",
		"Mozilla Firefox"	  => "((Firebird)|(Firefox))",
		"Galeon" 			  => "(Galeon)",
	 	"Mozilla"			  => "(Gecko)",
		"MyIE"				  => "(MyIE)",
		"Lynx" 				  => "(Lynx)",
		"Netscape" 			  => "((Mozilla/4\.75)|(Netscape6)|(Mozilla/4\.08)|(Mozilla/4\.5)|(Mozilla/4\.6)|(Mozilla/4\.79))",
		"Konqueror"			  => "(Konqueror)",
		"Internet Explorer 9" => "((MSIE 9\.[0-9]+))",		  
		"Internet Explorer 8" => "((MSIE 8\.[0-9]+))",
		"Internet Explorer 7" => "((MSIE 7\.[0-9]+))",
		"Internet Explorer 6" => "((MSIE 6\.[0-9]+))",
		"Internet Explorer 5" => "((MSIE 5\.[0-9]+))",
	 	"Internet Explorer 4" => "((MSIE 4\.[0-9]+))",
		"Chrome"              => "((Chrome))"
	);

	foreach($browsers as $browser => $pattern) {
		if(preg_match($pattern, $_SERVER["HTTP_USER_AGENT"])) {
			if($browser == "Mozilla") {
				if(strstr($_SERVER["HTTP_USER_AGENT"], "Chrome") !== FALSE) {
					return "Google Chrome";
				}
				
				if(strstr($_SERVER["HTTP_USER_AGENT"], "Safari") !== FALSE) {
					return "Safari";
				}
			} else {
				$browser = str_replace(" 9", "", $browser);
				$browser = str_replace(" 8", "", $browser);
				$browser = str_replace(" 7", "", $browser);
				$browser = str_replace(" 6", "", $browser);
				
				return $browser;	
			}
		}
	}
	
	return "Unknown";
}

function isMobile($iphone = TRUE, $ipad = TRUE, $android = TRUE , $opera = TRUE, $blackberry = TRUE, $palm = TRUE, $windows = TRUE, $redirect = FALSE, $desktopRedirect = FALSE) {
	$mobile    = FALSE; 
  	$userAgent = $_SERVER["HTTP_USER_AGENT"];
  	$accept    = $_SERVER["HTTP_ACCEPT"]; 
	
	$array = array(
		"1207" => "1207", "3gso" => "3gso", "4thp" => "4thp", "501i" => "501i", "502i" => "502i", "503i" => "503i",
		"504i" => "504i", "505i" => "505i", "506i" => "506i", "6310" => "6310", "6590" => "6590", "770s" => "770s",
		"802s" => "802s", "a wa" => "a wa", "acer" => "acer", "acs-" => "acs-", "airn" => "airn", "alav" => "alav",
		"asus" => "asus", "attw" => "attw", "au-m" => "au-m", "aur " => "aur ", "aus " => "aus ", "abac" => "abac",
		"acoo" => "acoo", "aiko" => "aiko", "alco" => "alco", "alca" => "alca", "amoi" => "amoi", "anex" => "anex",
		"anny" => "anny", "anyw" => "anyw", "aptu" => "aptu", "arch" => "arch", "argo" => "argo", "bell" => "bell", 
		"bird" => "bird", "bw-n" => "bw-n", "bw-u" => "bw-u", "beck" => "beck", "benq" => "benq", "bilb" => "bilb", 
		"blac" => "blac", "c55/" => "c55/", "cdm-" => "cdm-", "chtm" => "chtm", "capi" => "capi", "cond" => "cond", 
		"craw" => "craw", "dall" => "dall", "dbte" => "dbte", "dc-s" => "dc-s", "dica" => "dica", "ds-d" => "ds-d", 
		"ds12" => "ds12", "dait" => "dait", "devi" => "devi", "dmob" => "dmob", "doco" => "doco", "dopo" => "dopo", 
		"el49" => "el49", "erk0" => "erk0", "esl8" => "esl8", "ez40" => "ez40", "ez60" => "ez60", "ez70" => "ez70", 
		"ezos" => "ezos", "ezze" => "ezze", "elai" => "elai", "emul" => "emul", "eric" => "eric", "ezwa" => "ezwa", 
		"fake" => "fake", "fly-" => "fly-", "fly_" => "fly_", "g-mo" => "g-mo", "g1 u" => "g1 u", "g560" => "g560", 
		"gf-5" => "gf-5", "grun" => "grun", "gene" => "gene", "go.w" => "go.w", "good" => "good", "grad" => "grad", 
		"hcit" => "hcit", "hd-m" => "hd-m", "hd-p" => "hd-p", "hd-t" => "hd-t", "hei-" => "hei-", "hp i" => "hp i", 
		"hpip" => "hpip", "hs-c" => "hs-c", "htc " => "htc ", "htc-" => "htc-", "htca" => "htca", "htcg" => "htcg", 
		"htcp" => "htcp", "htcs" => "htcs", "htct" => "htct", "htc_" => "htc_", "haie" => "haie", "hita" => "hita", 
		"huaw" => "huaw", "hutc" => "hutc", "i-20" => "i-20", "i-go" => "i-go", "i-ma" => "i-ma", "i230" => "i230", 
		"iac"  => "iac",  "iac-" => "iac-", "iac/" => "iac/", "ig01" => "ig01", "im1k" => "im1k", "inno" => "inno", 
		"iris" => "iris", "jata" => "jata", "java" => "java", "kddi" => "kddi", "kgt"  => "kgt", "kgt/"  => "kgt/", 
		"kpt " => "kpt ", "kwc-" => "kwc-", "klon" => "klon", "lexi" => "lexi", "lg g" => "lg g", "lg-a" => "lg-a", 
		"lg-b" => "lg-b", "lg-c" => "lg-c", "lg-d" => "lg-d", "lg-f" => "lg-f", "lg-g" => "lg-g", "lg-k" => "lg-k", 
		"lg-l" => "lg-l", "lg-m" => "lg-m", "lg-o" => "lg-o", "lg-p" => "lg-p", "lg-s" => "lg-s", "lg-t" => "lg-t", 
		"lg-u" => "lg-u", "lg-w" => "lg-w", "lg/k" => "lg/k", "lg/l" => "lg/l", "lg/u" => "lg/u", "lg50" => "lg50", 
		"lg54" => "lg54", "lge-" => "lge-", "lge/" => "lge/", "lynx" => "lynx", "leno" => "leno", "m1-w" => "m1-w", 
		"m3ga" => "m3ga", "m50/" => "m50/", "maui" => "maui", "mc01" => "mc01", "mc21" => "mc21", "mcca" => "mcca", 
		"medi" => "medi", "meri" => "meri", "mio8" => "mio8", "mioa" => "mioa", "mo01" => "mo01", "mo02" => "mo02", 
		"mode" => "mode", "modo" => "modo", "mot " => "mot ", "mot-" => "mot-", "mt50" => "mt50", "mtp1" => "mtp1", 
		"mtv " => "mtv ", "mate" => "mate", "maxo" => "maxo", "merc" => "merc", "mits" => "mits", "mobi" => "mobi", 
		"motv" => "motv", "mozz" => "mozz", "n100" => "n100", "n101" => "n101", "n102" => "n102", "n202" => "n202", 
		"n203" => "n203", "n300" => "n300", "n302" => "n302", "n500" => "n500", "n502" => "n502", "n505" => "n505", 
		"n700" => "n700", "n701" => "n701", "n710" => "n710", "nec-" => "nec-", "nem-" => "nem-", "newg" => "newg", 
		"neon" => "neon", "netf" => "netf", "noki" => "noki", "nzph" => "nzph", "o2 x" => "o2 x", "o2-x" => "o2-x", 
		"opwv" => "opwv", "owg1" => "owg1", "opti" => "opti", "oran" => "oran", "p800" => "p800", "pand" => "pand", 
		"pg-1" => "pg-1", "pg-2" => "pg-2", "pg-3" => "pg-3", "pg-6" => "pg-6", "pg-8" => "pg-8", "pg-c" => "pg-c", 
		"pg13" => "pg13", "phil" => "phil", "pn-2" => "pn-2", "pt-g" => "pt-g", "palm" => "palm", "pana" => "pana", 
		"pire" => "pire", "pock" => "pock", "pose" => "pose", "psio" => "psio", "qa-a" => "qa-a", "qc-2" => "qc-2", 
		"qc-3" => "qc-3", "qc-5" => "qc-5", "qc-7" => "qc-7", "qc07" => "qc07", "qc12" => "qc12", "qc21" => "qc21", 
		"qc32" => "qc32", "qc60" => "qc60", "qci-" => "qci-", "qwap" => "qwap", "qtek" => "qtek", "r380" => "r380", 
		"r600" => "r600", "raks" => "raks", "rim9" => "rim9", "rove" => "rove", "s55/" => "s55/", "sage" => "sage", 
		"sams" => "sams", "sc01" => "sc01", "sch-" => "sch-", "scp-" => "scp-", "sdk/" => "sdk/", "se47" => "se47", 
		"sec-" => "sec-", "sec0" => "sec0", "sec1" => "sec1", "semc" => "semc", "sgh-" => "sgh-", "shar" => "shar", 
		"sie-" => "sie-", "sk-0" => "sk-0", "sl45" => "sl45", "slid" => "slid", "smb3" => "smb3", "smt5" => "smt5", 
		"sp01" => "sp01", "sph-" => "sph-", "spv " => "spv ", "spv-" => "spv-", "sy01" => "sy01", "samm" => "samm", 
		"sany" => "sany", "sava" => "sava", "scoo" => "scoo", "send" => "send", "siem" => "siem", "smar" => "smar", 
		"smit" => "smit", "soft" => "soft", "sony" => "sony", "t-mo" => "t-mo", "t218" => "t218", "t250" => "t250", 
		"t600" => "t600", "t610" => "t610", "t618" => "t618", "tcl-" => "tcl-", "tdg-" => "tdg-", "telm" => "telm", 
		"tim-" => "tim-", "ts70" => "ts70", "tsm-" => "tsm-", "tsm3" => "tsm3", "tsm5" => "tsm5", "tx-9" => "tx-9", 
		"tagt" => "tagt", "talk" => "talk", "teli" => "teli", "topl" => "topl", "hiba" => "hiba", "up.b" => "up.b", 
		"upg1" => "upg1", "utst" => "utst", "v400" => "v400", "v750" => "v750", "veri" => "veri", "vk-v" => "vk-v", 
		"vk40" => "vk40", "vk50" => "vk50", "vk52" => "vk52", "vk53" => "vk53", "vm40" => "vm40", "vx98" => "vx98", 
		"virg" => "virg", "vite" => "vite", "voda" => "voda", "vulc" => "vulc", "w3c " => "w3c ", "w3c-" => "w3c-", 
		"wapj" => "wapj", "wapp" => "wapp", "wapu" => "wapu", "wapm" => "wapm", "wig " => "wig ", "wapi" => "wapi", 
		"wapr" => "wapr", "wapv" => "wapv", "wapy" => "wapy", "wapa" => "wapa", "waps" => "waps", "wapt" => "wapt", 
		"winc" => "winc", "winw" => "winw", "wonu" => "wonu", "x700" => "x700", "xda2" => "xda2", "xdag" => "xdag", 
		"yas-" => "yas-", "your" => "your", "zte-" => "zte-", "zeto" => "zeto", "acs-" => "acs-", "alav" => "alav", 
		"alca" => "alca", "amoi" => "amoi", "aste" => "aste", "audi" => "audi", "avan" => "avan", "benq" => "benq", 
		"bird" => "bird", "blac" => "blac", "blaz" => "blaz", "brew" => "brew", "brvw" => "brvw", "bumb" => "bumb", 
		"ccwa" => "ccwa", "cell" => "cell", "cldc" => "cldc", "cmd-" => "cmd-", "dang" => "dang", "doco" => "doco", 
		"eml2" => "eml2", "eric" => "eric", "fetc" => "fetc", "hipt" => "hipt", "http" => "http", "ibro" => "ibro", 
		"idea" => "idea", "ikom" => "ikom", "inno" => "inno", "ipaq" => "ipaq", "jbro" => "jbro", "jemu" => "jemu", 
		"java" => "java", "jigs" => "jigs", "kddi" => "kddi", "keji" => "keji", "kyoc" => "kyoc", "kyok" => "kyok", 
		"leno" => "leno", "lg-c" => "lg-c", "lg-d" => "lg-d", "lg-g" => "lg-g", "lge-" => "lge-", "libw" => "libw", 
		"m-cr" => "m-cr", "maui" => "maui", "maxo" => "maxo", "midp" => "midp", "mits" => "mits", "mmef" => "mmef", 
		"mobi" => "mobi", "mot-" => "mot-", "moto" => "moto", "mwbp" => "mwbp", "mywa" => "mywa", "nec-" => "nec-", 
		"newt" => "newt", "nok6" => "nok6", "noki" => "noki", "o2im" => "o2im", "opwv" => "opwv", "palm" => "palm", 
		"pana" => "pana", "pant" => "pant", "pdxg" => "pdxg", "phil" => "phil", "play" => "play", "pluc" => "pluc", 
		"port" => "port", "prox" => "prox", "qtek" => "qtek", "qwap" => "qwap", "rozo" => "rozo", "sage" => "sage", 
		"sama" => "sama", "sams" => "sams", "sany" => "sany", "sch-" => "sch-", "sec-" => "sec-", "send" => "send", 
		"seri" => "seri", "sgh-" => "sgh-", "shar" => "shar", "sie-" => "sie-", "siem" => "siem", "smal" => "smal", 
		"smar" => "smar", "sony" => "sony", "sph-" => "sph-", "symb" => "symb", "t-mo" => "t-mo", "teli" => "teli", 
		"tim-" => "tim-", "tosh" => "tosh", "treo" => "treo", "tsm-" => "tsm-", "upg1" => "upg1", "upsi" => "upsi", 
		"vk-v" => "vk-v", "voda" => "voda", "vx52" => "vx52", "vx53" => "vx53", "vx60" => "vx60", "vx61" => "vx61", 
		"vx70" => "vx70", "vx80" => "vx80", "vx81" => "vx81", "vx83" => "vx83", "vx85" => "vx85", "wap-" => "wap-", 
		"wapa" => "wapa", "wapi" => "wapi", "wapp" => "wapp", "wapr" => "wapr", "webc" => "webc", "whit" => "whit", 
		"winw" => "winw", "wmlb" => "wmlb", "xda-" => "xda-"
	);
	
  	switch(TRUE) {
    	case (preg_match('/ipad/i', $userAgent)):
      		$mobile = $ipad; 
      		$mark   = 'Apple iPad';
      
			if(substr($ipad, 0, 4) === "http") { 
        		$redirect = $ipad; 
    		} 
    	break;

    	case (preg_match('/ipod/i', $userAgent) or preg_match('/iphone/i', $userAgent)):
      		$mobile = $iphone; 
      		$mark   = 'Apple';
      
			if(substr($iphone, 0, 4) === "http") { 
        		$redirect = $iphone; 
      		}
    	break; 
    
		case (preg_match('/android/i', $userAgent)):
      		$mobile = $android; 
      		$mark   = 'Android';

      		if(substr($android, 0, 4) === "http") { 
        		$redirect = $android; 
      		}
    	break; 
    	
		case (preg_match('/opera mini/i', $userAgent)):
      		$mobile = $opera; 
      		$mark   = 'Opera';
      
			if(substr($opera, 0, 4) === "http") { 
        		$redirect = $opera; 
      		} 
    	break; 

    	case (preg_match('/blackberry/i', $userAgent)):
      		$mobile = $blackberry; 
      		$mark   = 'Blackberry';
      
			if(substr($blackberry, 0, 4) === "http") {
        		$redirect = $blackberry; 
      		} 
    	break; 
    	
		case (preg_match('/(pre\/|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i', $userAgent)):
      		$mobile = $palm;
      		$mark   = 'Palm';
      
			if(substr($palm, 0, 4) === "http") {
        		$redirect = $palm;
      		} 
    	break;

    	case (preg_match('/(iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile)/i', $userAgent)):
      		$mobile = $windows; 
      		$mark   = 'Windows Smartphone';

      		if(substr($windows, 0, 4) === "http") { 
      			$redirect = $windows; 
      		} 
    	break; 

    	case (preg_match('/(mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320|vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo)/i', $userAgent)):
      		$mobile = TRUE;
      		$mark   = 'Other';
    	break;

    	case ((strpos($accept, "text/vnd.wap.wml") > 0) or (strpos($accept, "application/vnd.wap.xhtml+xml") > 0)): 
      		$mobile = TRUE;
      		$mark   = 'Wap';
    	break;

    	case (isset($_SERVER["HTTP_X_WAP_PROFILE"]) or isset($_SERVER["HTTP_PROFILE"])):
      		$mobile = TRUE; 
      		$mark   = 'Unknwon';
    	break; 

    	case (in_array(strtolower(substr($userAgent, 0, 4)), $array)):
			$mobile = TRUE;
      		$mark   = 'List';
    	break;

    	default:
      		$mobile = FALSE;
      		$mark   = 'Desktop';
    	break; 
	}

  	if($redirect = ($mobile === TRUE) ? $redirect : $desktopRedirect) {
    	header('Location: '. $redirect);
		
		exit;
  	} else { 
		if(!$mobile) {
			return FALSE; 
		} else {
			return array("mobile" => $mobile, "mark" => $mark);
		}
	}
}