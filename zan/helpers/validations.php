<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

if (!function_exists("is")) {
	function is($var = null, $value = null) 
	{
		return (isset($var) and $var === $value) ? true : false;
	}
}

if (!function_exists("isName")) {
	function isName($name)
	{
		if (strlen($name) < 7) {
			return false;
		}

		$parts = explode(" ", $name);
		$count = count($parts);

		if ($count > 1) {
			for ($i = 0; $i <= $count; $i++) {
				if (isset($parts[$i]) and strlen($parts[$i]) > 25) {
					return false;
				}
			}
		} else {
			return false;
		} 

		return true;
	}
}

if (!function_exists("isEmail")) {
	function isEmail($email)
	{
		return (filter_var($email, FILTER_VALIDATE_EMAIL)) ? true : false;
	}
}

if (!function_exists("isImage")) {
	function isImage($image)
	{
	    return (getimagesize($image)) ? true : false;
	}
}

if (!function_exists("isInjection")) {
	function isInjection($text, $count = 1)
	{
		if (is_string($text)) {
			$text = html_entity_decode($text);
			
			if (substr_count($text, "scriptalert") >= $count) {
				return true;
			} elseif (substr_count($text, ";/alert") >= $count) {
				return true;
			} elseif (substr_count($text, "<script") >= $count) {
				return true;
			} elseif (substr_count($text, "<if rame") >= $count) {
				return true;
			}	
		}
		
		return false;
	}
}

if (!function_exists("isIP")) {
	function isIP($IP)
	{
		return filter_var($IP, FILTER_VALIDATE_IP) ? true : false;
	}
}
if (!function_exists("isSPAM")) {
	function isSPAM($string, $max = 1)
	{
		$words = array(	
			"www", ".co.uk", ".jp", ".ch", ".info", ".mobi", ".us", ".ca", ".ws", ".ag", 
			".com.co", ".net.co", ".com.ag", ".net.ag", ".it", ".fr", ".tv", ".am", ".asia", ".at", ".be", ".cc", ".de", ".es", ".com.es", ".eu", 
			".fm", ".in", ".tk", ".com.mx", ".nl", ".nu", ".tw", ".vg", "sex", "porn", "fuck", "buy", "free", "dating", "viagra", "money", "dollars", 
			"payment", "website", "games", "toys", "poker", "cheap"
		);
						
	    $count = 0;
	    $string = strtolower($string);
	    
	    if (is_array($words)) {
			foreach ($words as $word) {
				$count += substr_count($string, $word);
			}
		}
		
		return ($count > $max) ? true : false;
	}
}

if (!function_exists("isVulgar")) {
	function isVulgar($string, $max = 1)
	{	
		$words = array(	
			"puto", "puta", "perra", "tonto", "tonta", "pene", "pito", "chinga tu madre", "hijo de puta", "verga", "pendejo", "baboso",
			"estupido", "idiota", "joto", "gay", "maricon", "marica", "chingar", "jodete", "pinche", "panocha", "vagina", "zorra", "fuck",
			"chingada", "cojer", "imbecil", "pendeja", "piruja", "puerca", "polla", "capullo", "gilipollas", "cabron", "cagada", "cago", 
			"cagar", "mierda", "marrano", "porno", "conche", "tu puta madre", "putas", "putos", "pendejas", "pendejos", "pendejadas", 
			"mamadas", "lesbianas", "coño", "huevon", "sudaca", "fucker", "ramera", "fuck", "bitch", "malparido", "hijuputa", "gonorrea", 
			"marica e' polvado", "boludo", "chimba", "chunchurria", "gorsovia", "condon", "gorzobia", "bastardo", "fueputa", "zunga", 
			"flaite", "reculiao", "reculiado", "culiado", "culiao", "puñetero", "ñero", "gil", "mujerzuelo", "mujerzuela", "puberto", 
			"emo", "emopuberto", "putete", "pendejete", "pajero", "putito", "putita", "mamar en reversa", "clitoris", "penudo", "hijueputa",
			"galondra", "piroba", "guaricha", "catre", "catre hijueputa", "putazo", "trollazo", "putaso", "putisimo", "putillo", "pete", 
			"puñete", "puñeñe", "penitente", "vergacion", "vergatario", "nawara", "porqueria", "pajuo", "becerro", "mieldero", "tarado",
			"mierdero", "aweboniao", "aweboniado", "aweboniar", "a webo", "orto", "horto", "coger", "imvecil", "inbecil", "imbesil", 
			"imvesil", "mames", "guaton", "callampa", "callapa", "cayapa", "ermio", "vergon", "vergudo", "verguita", "xuxa", "andate", 
			"kaga", "kagar", "kagon", "vaca", "kear", "vacan", "bakan", "bakano", "vacano", "bagina", "pichicho", "calzon", "tanga", 
			"cagado", "cagadisimo", "caguengue", "cojido", "cojida", "orgasmo", "anal", "ano", "catimba", "guevo", "lamepene", "pucha",
			"coño he tu madre", "cojio", "culero", "culito", "malpario", "verguero", "vergero", "nojoa", "nojodas", "jolines", "pichi", 
			"pinshe", "inche", "reputisima", "infeliz", "concha rota", "chupame el piko", "chupame el pico", "huevon","camboyana", 
			"pelao", "pelado", "chuchalaloma", "conchetumare", "chuchetumare", "guaite", "ñoño", "ñoña", "charquican", "piko", "menem",
			"huevón", "huevona", "huevada", "gei", "gey", "marikon", "marika", "marikona", "culia", "maraca", "ahuevonado", "sexo", 
			"agilado", "maraka kulia", "razorra", "cokitos", "chupala", "locote", "pichanga", "cabro", "hoe", "whore", "asshole", "asswipe",
			"kulia", "geicha", "cacha", "culiando", "conxhetumare", "conxetumare", "qliao", "qlia", "flaitongo", "ctm", "reconxetumare", 
			"webn", "yanaruntu", "siquisapa", "mariposon", "maripozon", "jijuna", "ñuñusapa", "pajarincama", "parido", "pirobo", "locota", 
			"baboso", "fuck", "bitch", "ass", "jackass", "jackas", "dumbas", "dumbass", "motherfucker", "motherfocker", "shit", "damn",
			"rape", "bitchass", "bullshit", "douche", "douchebag", " dipshit", "lesbians", "homo", "ass rape", "filho", "xereca", "escroto", 
			"piranha",  "jetea", "jetear", "getea", "getear", "que pedo", "morrillo", "morro", "carajo", "carajito", "merguevo", "merhuevo",
			"rosquete", "chimbombo", "brichi", "cojonudo", "cojudo", "friega", "pta madre", "follar", "follado", "follas", "pedo",
			"pishes", "shishotas", "chiches", "chichotas", "senos", "bubis", "puerco", "mamon", "mamón" 
		);
						
	    $count = 0;
	     
	    $string = strtolower($string);
	    
	    if (is_array($words)) {
			foreach ($words as $word) {
				$count += substr_count($string, $word);
			}
		}

		return ($count >= $max) ? true : false;
	}
}

if (!function_exists("isNumber")) {
	function isNumber($number)
	{
		$number = (int) $number;
		
		if ($number > 0) {
			return true;	
		}
		
		return false;
	}
}

if (!function_exists("isMethod")) {
	function isMethod($method, $Controller)
	{
		try {
		    $Reflection = new ReflectionMethod($Controller, $method);
		    return true;
		} catch (Exception $e) {
		    return false;
		}
	}
}

if (!function_exists("isController")) {
	function isController($controller, $application = null, $principal = false)
	{
		if ($application === true) {
			if (file_exists($controller)) {
				return true;
			}
		} else { 
			if ($principal) {
				if ($controller === $application) {
					$file = "www/applications/$application/controllers/$controller.php";

					if (file_exists($file)) {
						return true;	
					}				
				} else {
					return false;
				}
			}			

			return file_exists("www/applications/$application/controllers/$controller.php") ? true : false;		
		}
	}
}

if (!function_exists("isLeapYear")) {
	function isLeapYear($year)
	{
		return ((((int) $year % 4 === 0) and ((int) $year % 100 !== 0 ) or ((int) $year % 400 === 0)));
	}
}

if (!function_exists("isDay")) {
	function isDay($day)
	{	
		return (strlen($day) === 2 and $day > 0 and $day <= 31) ? true : false;	
	}
}

if (!function_exists("isMonth")) {
	function isMonth($month)
	{
		return (strlen($month) === 2 and $month > 0 and $month <= 12) ? true : false;
	}
}

if (!function_exists("isYear")) {
	function isYear($year)
	{
		return (strlen($year) === 4 and $year >= 1950 and $year <= date("Y")) ? true : false;
	}
}