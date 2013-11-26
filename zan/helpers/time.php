<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

if (!function_exists("getHour")) {
	function getHour($date)
	{
		$date = explode(" ", $date);
		$time = (count($date) > 1) ? explode(":", $date[1]) : explode(":", $date[0]);
		$hours = (int) $time[0];
		$minutes = $time[1];
		
		if ($hours > 12) {			
			$day = "P.M."; 
			
			if ($hours === 13) {
				$hour = "01";
			} elseif ($hours === 14) {
				$hour = "02";
			} elseif ($hours === 15) {
				$hour = "03";
			} elseif ($hours === 16) {
				$hour = "04";
			} elseif ($hours === 17) {
				$hour = "05";
			} elseif ($hours === 18) {
				$hour = "06";
			} elseif ($hours === 19) {
				$hour = "07";
			} elseif ($hours === 20) {
				$hour = "08";
			} elseif ($hours === 21) {
				$hour = "09";
			} elseif ($hours === 22) {
				$hour = "10";
			} elseif ($hours === 23) {
				$hour = "11";
			} elseif ($hours === 00) {
				$hour = "12";
			}
		} else {
			$day = "A.M.";
			$hour = $hours;
		}
		
		return "$hour : $minutes $day";
	}
}

if (!function_exists("getSeconds")) {
	function getSeconds($time)
	{
		return intval($time	/ 1000) ." ". __("seconds");
	}
}

if (!function_exists("getTime")) {
	function getTime($date)
	{
		return strtotime($date);	
	}
}

if (!function_exists("howLong")) {
	function howLong($value)
	{
		$language = whichLanguage();
		$default = "H:i:s j-n-Y";
		
		if (stristr($value, "-") or stristr($value, ":") or stristr($value, ".") or stristr($value, ",")) {
			if (stristr($value, "[")) {
				$parts  = explode("[", $value);
				$value  = trim($parts[0]);
				$format = str_replace("]", "", $parts[1]);
			} else {
				$format = $default;
			}

			$value = str_replace("-", " ", $value);
			$value = str_replace(":", " ", $value);
			$value = str_replace(".", " ", $value);
			$value = str_replace(",", " ", $value);
			$number = explode(" ", $value);
			$format = str_replace("-", " ", $format);
			$format = str_replace(":", " ", $format);
			$format = str_replace(".", " ", $format);
			$format = str_replace(",", " ", $format);
			$format = str_replace("d", "j", $format);
			$format = str_replace("m", "n", $format);
			$format = str_replace("G", "H", $format);
	    	$letter = explode(" ", $format);
			$relation[$letter[0]] = $number[0];
			$relation[$letter[1]] = $number[1];
			$relation[$letter[2]] = $number[2];
			$relation[$letter[3]] = $number[3];
			$relation[$letter[4]] = $number[4];
			$relation[$letter[5]] = $number[5];
	    	$value = mktime($relation["H"], $relation["i"], $relation["s"], $relation["n"], $relation["j"], $relation["Y"]);
		}

		$time = time() - $value;
		
		if ($time >= 2116800) {
			$date = __("on") ." ". now(2);
		}

		if ($time < 30242054.045) {
			$rTime = round($time / 2629743.83);
			
			if ($rTime > 1) {
				if ($language === "English") {
					$date = $rTime ." months ago";
				} elseif ($language === "Spanish") {
					$date = "hace  ". $rTime ." meses";
				} elseif ($language === "French") {
					$date = "il ya ". $rTime ." mois";
				} elseif ($language === "Portuguese") {
					$date = "há ". $rTime ." meses";
				} elseif ($language === "Italian") {
					$date = $rTime ." mesi";
				}
			} else {	
				if ($language === "English") {
					$date = $rTime ." month ago";
				} elseif ($language === "Spanish") {
					$date = "hace  ". $rTime ." mes";
				} elseif ($language === "French") {
					$date = "il ya ". $rTime ." mois";		
				} elseif ($language === "Portuguese") {
					$date = $rTime ." m&ecirc;s atr&aacute;s";
				} elseif ($language === "Italian") {
					$date = $rTime ." mese fa";
				}
			}
		}
		
		if ($time < 2116800) {
			$rTime = round($time / 604800);
			
			if ($rTime > 1) {
				if ($language === "English") {
					$date = $rTime ." weeks ago";
				} elseif ($language === "Spanish") {
					$date = "hace  ". $rTime ." semanas";
				} elseif ($language === "French") {
					$date = "il ya ". $rTime ." semaines";		
				} elseif ($language === "Portuguese") {
					$date = $rTime ." semanas atr&aacute;s";
				} elseif ($language === "Italian") {
					$date = $rTime ." settimane fa";
				}
			} else {
				if ($language === "English") {
					$date = $rTime ." week ago";
				} elseif ($language === "Spanish") {
					$date = "hace  ". $rTime ." semana";
				} elseif ($language === "French") {
					$date = "il ya ". $rTime ." semaine";
				} elseif ($language === "Portuguese") {
					$date = $rTime ." semana atr&aacute;s";
				} elseif ($language === "Italian") {
					$date = $rTime ." settimana fa";
				}
			}
		}
		
		if ($time < 561600) { 
			$rTime = (int) round($time / 86400);
			
			if ($rTime === 1) {
				$date = __("yesterday");				
			}
			
			if ($rTime === 2) {
				$date = __("before yesterday");		
			}
			
			if ($rTime > 2) {
				if ($language === "English") {
					$date = $rTime ." days ago";
				} elseif ($language === "Spanish") {
					$date = "hace ". $rTime ." d&iacute;as";
				} elseif ($language === "French") {
					$date = "il ya ". $rTime ." jours";
				} elseif ($language === "Portuguese") {
					$date = $rTime ." dias atr&aacute;s";
				} elseif ($language === "Italian") {
					$date = $rTime ." giorni fa";
				}
			}
		}
		
		if ($time < 84600) {
			$rTime = round($time / 3600);
					
			if ($rTime > 1) {
				if ($language === "English") {
					$date = $rTime ." hours ago";
				} elseif ($language === "Spanish") {
					$date = "hace  ". $rTime ." horas";
				} elseif ($language === "French") {
					$date = "il ya ". $rTime ." heures";
				} elseif ($language === "Portuguese") {
					$date = $rTime ." horas atr&aacute;s";
				} elseif ($language === "Italian") {
					$date = $rTime ." ore fa";
				}
			} else {
				if ($language === "English") {
					$date = $rTime ." hour ago";
				} elseif ($language === "Spanish") {
					$date = "hace  ". $rTime ." hora";
				} elseif ($language === "French") {
					$date = "il ya ". $rTime ." heures";
				} elseif ($language === "Portuguese") {
					$date = $rTime ." hora atr&aacute;s";
				} elseif ($language === "Italian") {
					$date = $rTime ." ora fa";
				}
			}
			
			if ($time > 4200 and $time < 5400) {
				$date = __("more than an hour ago");
			}
		}

		if ($time < 3570) {
			$rTime = round($time / 60);
			
			if ($rTime > 1) {
				if ($language === "English") {
					$date = $rTime ." minutes ago";
				} elseif ($language === "Spanish") {
					$date = "hace  ". $rTime ." minutos";
				} elseif ($language === "French") {
					$date = "il ya ". $rTime ." minutes";
				} elseif ($language === "Portuguese") {
					$date = $rTime ." minutos atr&aacute;s";
				} elseif ($language === "Italian") {
					$date = $rTime ." minuti fa";
				}
			} else {
				if ($language === "English") {
					$date = $rTime ." minute ago";
				} elseif ($language === "Spanish") {
					$date = "hace  ". $rTime ." minuto";
				} elseif ($language === "French") {
					$date = "il ya ". $rTime ." minute";
				} elseif ($language === "Portuguese") {
					$date = $rTime ." minuto atr&aacute;s";
				} elseif ($language === "Italian") {
					$date = $rTime ." minuto fa";
				}
			}
			
			if ($time < 60) {
				if ($language === "English") {
					$date = $time ." seconds ago";
				} elseif ($language === "Spanish") {
					$date = "hace  ". $time ." segundos";
				} elseif ($language === "French") {
					$date = "il ya ". $time ." secondes";
				} elseif ($language === "Portuguese") {
					$date = $time ." segundos atr&aacute;s";
				} elseif ($language === "Italian") {
					$date = $time ." secondi fa";
				}
			}
			
			if ($time <= 3) {
				$date = __("now");
			}
		}
		
		return $date;
	}
}

if (!function_exists("month")) {
	function month($month)
	{
		$month = (int) $month;
		
		if ($month === 1) {
			return __("January");
		} elseif ($month === 2) {
			return __("February");
		} elseif ($month === 3) {
			return __("March");
		} elseif ($month === 4) {
			return __("April");
		} elseif ($month === 5) {
			return __("May");
		} elseif ($month === 6) {
			return __("June");
		} elseif ($month === 7) {
			return __("July");
		} elseif ($month === 8) {
			return __("August");
		} elseif ($month === 9) {
			return __("September");
		} elseif ($month === 10) {
			return __("October");
		} elseif ($month === 11) {
			return __("November");
		} elseif ($month === 12) {
			return __("December");
		}
	}
}

if (!function_exists("now")) {
	function now($format, $hour = false, $language = null)
	{		
		$language = is_null($language) ? whichLanguage() : $language;
		
		if ($hour === true) {	
			$time = time() + 7200;
			$hours = (int) date("H", $time);
			$minutes = date("i", $time);
			$seconds = date("s", $time);
			
			if ($hours > 12) {
				$hour = ($hours === 13) ? "01" : $hours;
				$hour = ($hours === 14) ? "02" : $hours;
				$hour = ($hours === 15) ? "03" : $hours;
				$hour = ($hours === 16) ? "04" : $hours;
				$hour = ($hours === 17) ? "05" : $hours;
				$hour = ($hours === 18) ? "06" : $hours;
				$hour = ($hours === 19) ? "07" : $hours;
				$hour = ($hours === 20) ? "08" : $hours;
				$hour = ($hours === 21) ? "09" : $hours;
				$hour = ($hours === 22) ? "10" : $hours;
				$hour = ($hours === 23) ? "11" : $hours;
				$hour = ($hours === 00) ? "12" : $hours;

				return "$hour:$minutes P.M.";				
			} 
			
			return "$hours:$minutes A.M.";
		}
				
		if ($format === 1) {					
			return date("d") . SH . date("m") . SH . date("y");
		} elseif ($format === 2) {					
			$day = __(date("l")); 
			$month = __(date("F"));			
					
			if ($language === "English") {
				return "$day, $month ". date("d") .", ". date("Y");
			} elseif ($language === "Spanish") {
				return "$day, ". date("d") ." de $month de ". date("Y"); 
			} elseif ($language === "French") {
				return "$day, ". date("d") ." $month ". date("Y");
			} else {
				return "$day, $month ". date("d") .", ". date("Y"); 
			}
		} elseif ($format === 3) {
			return date("d/m/Y H:i:s", time()); 			
		} elseif ($format === 4) {
			return time();
		} elseif ($format === 5) {
			return strtotime($hour);
		} elseif ($format === 6) {
			return date("m/d/Y H:i:s", time());
		} elseif ($format === 7) {
			return date("Y-m-d H:i:s", time());
		} elseif ($format === 8) {
			return date("Y-m-d", time());
		} else {
			return date("d/m/Y", time());				  
		}
	}
}

if (!function_exists("getAllDays")) {
	function getAllDays($day, $endDate)
	{
		$currentDate = strtotime($day, strtotime(now(8)));
	  	$endDate = strtotime($endDate, strtotime(now(8)));	 
		$days = array();

		while ($currentDate < $endDate) {
			$day = date("l d \of F Y", $currentDate);
			$day = str_replace("Saturday", __("Saturday"), $day);
			$day = str_replace("January", __("January"), $day);
			$day = str_replace("February", __("February"), $day);
			$day = str_replace("March", __("March"), $day);
			$day = str_replace("April", __("April"), $day);
			$day = str_replace("May", __("May"), $day);
			$day = str_replace("June", __("June"), $day);
			$day = str_replace("July", __("July"), $day);
			$day = str_replace("August", __("August"), $day);
			$day = str_replace("September", __("September"), $day);
			$day = str_replace("October", __("October"), $day);
			$day = str_replace("November", __("November"), $day);
			$day = str_replace("December", __("December"), $day);
			$day = str_replace("of", __("of"), $day);
			$days[] = $day;
			$currentDate = strtotime("+1 week", $currentDate);
		}

		return $days;
	}
}