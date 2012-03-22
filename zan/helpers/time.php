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
 * Time Helper
 *
 * 
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	helpers
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/helpers/time_helper
 */

/**
 * getHour
 *
 * @param $date
 * @return string value
 */ 
function getHour($date) {
	$date = explode(" ", $date);

	if(count($date) > 1) {
		$time = explode(":", $date[1]);
	} else {
		$time = explode(":", $date[0]);
	}
	
	$hours   = (int) $time[0];
	$minutes = $time[1];
	
	if($hours > 12) {			
		$day = "P.M."; 
		
		if($hours === 13) {
			$hour = "01";
		} elseif($hours === 14) {
			$hour = "02";
		} elseif($hours === 15) {
			$hour = "03";
		} elseif($hours === 16) {
			$hour = "04";
		} elseif($hours === 17) {
			$hour = "05";
		} elseif($hours === 18) {
			$hour = "06";
		} elseif($hours === 19) {
			$hour = "07";
		} elseif($hours === 20) {
			$hour = "08";
		} elseif($hours === 21) {
			$hour = "09";
		} elseif($hours === 22) {
			$hour = "10";
		} elseif($hours === 23) {
			$hour = "11";
		} elseif($hours === 00) {
			$hour = "12";
		}
	} else {
		$day  = "A.M.";
		$hour = $hours;
	}
	
	return "$hour : $minutes $day";
}

function getSeconds($time) {
	return intval($time	/ 1000) ." ". __("seconds");
}

function getTime($date) {
	return strtotime($date);	
}

function howLong($value) {
	$language = whichLanguage();
	$default  = "H:i:s j-n-Y";
	
	if(stristr($value, "-") or stristr($value, ":") or stristr($value, ".") or stristr($value, ",")) {
		if(stristr($value, "[")) {
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
	
	if($time >= 2116800) {
		$date = __("on") ." ". now(2);
	}

	if($time < 30242054.045) {
		$rTime = round($time / 2629743.83);
		
		if($rTime > 1) {
			if($language === "English") {
				$date = $rTime ." months ago";
			} elseif($language === "Spanish") {
				$date = "hace  ". $rTime ." meses";
			} elseif($language === "French") {
				$date = "il ya ". $rTime ." mois";
			} elseif($language === "Portuguese") {
				$date = "há ". $rTime ." meses";
			}
		} else {	
			if($language === "English") {
				$date = $rTime ." month ago";
			} elseif($language === "Spanish") {
				$date = "hace  ". $rTime ." mes";
			} elseif($language === "French") {
				$date = "il ya ". $rTime ." mois";		
			} elseif($language === "Portuguese") {
				$date = $rTime ." m&ecirc;s atr&aacute;s";
			}
		}
	}
	
	if($time < 2116800) {
		$rTime = round($time / 604800);
		
		if($rTime > 1) {
			if($language === "English") {
				$date = $rTime ." weeks ago";
			} elseif($language === "Spanish") {
				$date = "hace  ". $rTime ." semanas";
			} elseif($language === "French") {
				$date = "il ya ". $rTime ." semaines";		
			} elseif($language === "Portuguese") {
				$date = $rTime ." semanas atr&aacute;s";
			}
		} else {
			if($language === "English") {
				$date = $rTime ." week ago";
			} elseif($language === "Spanish") {
				$date = "hace  ". $rTime ." semana";
			} elseif($language === "French") {
				$date = "il ya ". $rTime ." semaine";
			} elseif($language === "Portuguese") {
				$date = $rTime ." semana atr&aacute;s";
			}
		}
	}
	
	if($time < 561600) { 
		$rTime = (int) round($time / 86400);
		
		if($rTime === 1) {
			$date = __("yesterday");				
		}
		
		if($rTime === 2) {
			$date = __("before yesterday");		
		}
		
		if($rTime > 2) {
			if($language === "English") {
				$date = $rTime ." days ago";
			} elseif($language === "Spanish") {
				$date = "hace ". $rTime ." d&iacute;as";
			} elseif($language === "French") {
				$date = "il ya ". $rTime ." jours";
			} elseif($language === "Portuguese") {
				$date = $rTime ." dias atr&aacute;s";
			}
		}
	}
	
	if($time < 84600) {
		$rTime = round($time / 3600);
				
		if($rTime > 1) {
			if($language === "English") {
				$date = $rTime ." hours ago";
			} elseif($language === "Spanish") {
				$date = "hace  ". $rTime ." horas";
			} elseif($language === "French") {
				$date = "il ya ". $rTime ." heures";
			} elseif($language === "Portuguese") {
				$date =  $rTime ." horas atr&aacute;s";
			}
		} else {
			if($language === "English") {
				$date = $rTime ." hour ago";
			} elseif($language === "Spanish") {
				$date = "hace  ". $rTime ." hora";
			} elseif($language === "French") {
				$date = "il ya ". $rTime ." heures";
			} elseif($language === "Portuguese") {
				$date = $rTime ." hora atr&aacute;s";
			}
		}
		
		if($time > 4200 and $time < 5400) {
			$date = __("more than an hour ago");
		}
	}

	if($time < 3570) {
		$rTime = round($time / 60);
		
		if($rTime > 1) {
			if($language === "English") {
				$date = $rTime ." minutes ago";
			} elseif($language === "Spanish") {
				$date = "hace  ". $rTime ." minutos";
			} elseif($language === "French") {
				$date = "il ya ". $rTime ." minutes";
			} elseif($language === "Portuguese") {
				$date = $rTime ." minutos atr&aacute;s";
			}
		} else {
			if($language === "English") {
				$date = $rTime ." minute ago";
			} elseif($language === "Spanish") {
				$date = "hace  ". $rTime ." minuto";
			} elseif($language === "French") {
				$date = "il ya ". $rTime ." minute";
			} elseif($language === "Portuguese") {
				$date = $rTime ." minuto atr&aacute;s";
			}
		}
		
		if($time < 60) {
			if($language === "English") {
				$date = $time ." seconds ago";
			} elseif($language === "Spanish") {
				$date = "hace  ". $time ." segundos";
			} elseif($language === "French") {
				$date = "il ya ". $time ." secondes";
			} elseif($language === "Portuguese") {
				$date = $time ." segundos atr&aacute;s";
			}
		}
		
		if($time <= 3) {
			$date = __("now");
		}
	}
	
	return $date;
}

function isLeapYear($year) {
	return ((((int) $year % 4 === 0) and ((int) $year % 100 !== 0 ) or ((int) $year % 400 === 0)));
}

function isDay($day) {
	if(strlen($day) === 2) {
		if($day > 0 and $day <= 31) {
			return TRUE;
		}
	}
	
	return FALSE;
}

function isMonth($month) {
	if(strlen($month) === 2) {
		if($month > 0 and $month <= 12) {
			return TRUE;
		}
	}
	
	return FALSE;
}

function isYear($year) {
	if(strlen($year) === 4) {
		if($year >= 1950 and $year <= date("Y")) {
			return TRUE;
		} 
	}
	
	return FALSE;
}

function month($month) {
	$month = (int) $month;
	
	if($month === 1) {
		return __("January");
	} elseif($month === 2) {
		return __("February");
	} elseif($month === 3) {
		return __("March");
	} elseif($month === 4) {
		return __("April");
	} elseif($month === 5) {
		return __("May");
	} elseif($month === 6) {
		return __("June");
	} elseif($month === 7) {
		return __("July");
	} elseif($month === 8) {
		return __("August");
	} elseif($month === 9) {
		return __("September");
	} elseif($month === 10) {
		return __("October");
	} elseif($month === 11) {
		return __("November");
	} elseif($month === 12) {
		return __("December");
	}
}

function now($format, $hour = FALSE, $language = NULL) {
	if(is_null($language)) {
		$language = whichLanguage();
	}
	
	if($hour) {	
		$time	   = time() + 7200;
		$hours 	   = (int) date("H", $time);
		$minutes   = date("i", $time);
		$seconds   = date("s", $time);
		
		if($hours > 12) {						
			if($hours === 13) {
				$hour = "01";
			} elseif($hours === 14) {
				$hour = "02";
			} elseif($hours === 15) {
				$hour = "03";
			} elseif($hours === 16) {
				$hour = "04";
			} elseif($hours === 17) {
				$hour = "05";
			} elseif($hours === 18) {
				$hour = "06";
			} elseif($hours === 19) {
				$hour = "07";
			} elseif($hours === 20) {
				$hour = "08";
			} elseif($hours === 21) {
				$hour = "09";
			} elseif($hours === 22) {
				$hour = "10";
			} elseif($hours === 23) {
				$hour = "11";
			} elseif($hours === 00) {
				$hour = "12";
			}
			
			return "$hour:$minutes P.M.";				
		} 
		
		return "$hours:$minutes A.M.";
	}
			
	if($format === 1) {					
		return date("d") . _sh . date("m") . _sh . date("y");
	} elseif($format === 2) {					
		$day = date("l"); 
		
		if($language === "Spanish") {
			if($day === "Monday") {
				$day = "Lunes";
			} elseif($day === "Tuesday") {
				$day = "Martes"; 
			} elseif($day === "Wednesday") {
				$day = "Miércoles";
			} elseif($day === "Thursday") {
				$day = "Jueves";
			} elseif($day === "Friday") {
				$day = "Viernes";
			} elseif($day === "Saturday") {
				$day = "Sábado";
			} elseif($day === "Sunday") {
				$day = "Domingo";
			}
		} elseif($language === "French") {
			if($day === "Monday") {
				$day = "Lundi";
			} elseif($day === "Tuesday") {
				$day = "Mardi"; 
			} elseif($day === "Wednesday") {
				$day = "Mercredi";
			} elseif($day === "Thursday") {
				$day = "Jeudi";
			} elseif($day === "Friday") {
				$day = "Vendredi";
			} elseif($day === "Saturday") {
				$day = "Samedi";
			} elseif($day === "Sunday") {
				$day = "Dimanche";
			}
		} elseif($language === "Portuguese") {
			if($day === "Monday") {
				$day = "Segunda-feira";
			} elseif($day === "Tuesday") {
				$day = "Ter&ccedil;a-feira"; 
			} elseif($day === "Wednesday") {
				$day = "Quarta-feira";
			} elseif($day === "Thursday") {
				$day = "Quinta-feira";
			} elseif($day === "Friday") {
				$day = "Sexta-feira";
			} elseif($day === "Saturday") {
				$day = "Sábado";
			} elseif($day === "Sunday") {
				$day = "Domingo";			
			}
		}
		
		$month = date("F");			
		
		if($language === "Spanish") {
			if($month === "January") {
				$month = "Enero";
			} elseif($month === "February") {
				$month = "Febrero";
			} elseif($month === "March") {
				$month = "Marzo";
			} elseif($month === "April") {
				$month = "Abril";
			} elseif($month === "May") {
				$month = "Mayo";
			} elseif($month === "June") {
				$month = "Junio";
			} elseif($month === "July") {
				$month = "Julio";
			} elseif($month === "August") {
				$month = "Agosto";
			} elseif($month === "September") {
				$month = "Septiembre";
			} elseif($month === "October") {
				$month = "Octubre"; 
			} elseif($month === "November") {
				$month = "Noviembre";
			} elseif($month === "December") {
				$month = "Diciembre";
			}
		} elseif($language === "French") {
			if($month === "January") {
				$month = "Janvier";
			} elseif($month === "February") {
				$month = "Février";
			} elseif($month === "March") {
				$month = "Mars";
			} elseif($month === "April") {
				$month = "Avril";
			} elseif($month === "May") {
				$month = "Mai";
			} elseif($month === "June") {
				$month = "Juin";
			} elseif($month === "July") {
				$month = "Juillet";
			} elseif($month === "August") {
				$month = "Ao&ucirc;t";
			} elseif($month === "September") {
				$month = "Septembre";
			} elseif($month === "October") {
				$month = "Octobre"; 
			} elseif($month === "November") {
				$month = "Novembre";
			} elseif($month === "December") {
				$month = "Décembre";
			}
		} elseif($language === "Portuguese") {
			if($month === "January") {
				$month = "Janeiro";
			} elseif($month === "February") {
				$month = "Fevereiro";
			} elseif($month === "March") {
				$month = "Março";
			} elseif($month === "April") {
				$month = "Abril";
			} elseif($month === "May") {
				$month = "Maio";
			} elseif($month === "June") {
				$month = "Junho";
			} elseif($month === "July") {
				$month = "Julho";
			} elseif($month === "August") {
				$month = "Agosto";
			} elseif($month === "September") {
				$month = "Setembro";
			} elseif($month === "October") {
				$month = "Outubro"; 
			} elseif($month === "November") {
				$month = "Novembro";
			} elseif($month === "December") {
				$month = "Dezembro";
			}
		}
		
		if($language === "English") {
			return "$day, $month ". date("d") .", ". date("Y");
		} elseif($language === "Spanish") {
			return "$day, ". date("d") ." de $month de ". date("Y"); 
		} elseif($language === "French") {
			return "$day, ". date("d") ." $month ". date("Y");
		} else {
			return "$day, $month ". date("d") .", ". date("Y"); 
		}
	} elseif($format === 3) {
		return date("d/m/Y H:i:s", time()); 			
	} elseif($format === 4) {
		return time();
	} elseif($format === 5) {
		return strtotime($hour);
	} elseif($format === 6) {
		return date("d/m/Y H:i:s", $hour);
	} elseif($format === 7) {
		return date("Y-m-d H:i:s");
	} else {
		return date("d/m/Y", $format);				  
	}
}