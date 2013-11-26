<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

if (!function_exists("formCheckbox")) {
	function formCheckbox($attributes = false)
	{
		if (isset($attributes) and is_array($attributes)) {
			$attrs = null;
			
			foreach ($attributes as $attribute => $value) {
				if ($attribute !== "position" and $attribute !== "text" and $attribute !== "type" and $attribute !== "checked") {
					$attrs .= ' '. strtolower($attribute) .'="'. encode($value) .'"';
				} else {
					$$attribute = encode($value);
				}
			}

			$check = (isset($checked) and $checked) ? ' checked="checked"' : null;
			
			if (isset($position) and $position === "left" and isset($text)) {
				return ''. decode($text) .' <input'. $attrs .' type="checkbox"'. $check .' /> ';
			} elseif (isset($position) and $position === "right" and isset($text)) {
				return '<input'. $attrs .' type="checkbox"'. $check .' /> '. decode($text) .' ';
			} elseif (isset($text)) {
				return ''. decode($text) .' <input'. $attrs .' type="checkbox"'. $check .' /> ';
			} else {
				return '<input'. $attrs .' type="checkbox"'. $check .' /> ';
			}
		} else {
			return null;
		}
	}
}

if (!function_exists("formClose")) {	
	function formClose()
	{
		$HTML = "</fieldset>";
		$HTML .= "</form>";
		return $HTML;
	}
}

if (!function_exists("formField")) {
	function formField($a = null, $text, $raquo = true)
	{
		$raquo = ($raquo === true) ? "&raquo; " : "";
		
		if (!is_null($a)) {
			$HTML = '<p class="field">';
			$HTML .= '<a '. $a .'>'. $raquo . $text .'</a>';
			$HTML .= '</p>';
		} else {
			$HTML = '<p class="field">';
			$HTML .= $raquo . $text; 
			$HTML .= '</p>';
		}
		
		return $HTML;
	}
}
	
if (!function_exists("formInput")) {	 
	function formInput($attributes = false, $disabled = false)
	{
		if (isset($attributes) and is_array($attributes)) {			
			$attrs = ($disabled) ? ' disabled ' : null;

			foreach ($attributes as $attribute => $value) {
				if ($attribute === "required") {
					$attrs .= ' required ';
				} elseif ($attribute === "events") {
					$attrs .= ' '. $value .' ';
				} elseif ($attribute !== "type" and $attribute !== "p" and $attribute !== "field" and $attribute !== "checked") {
					if (!preg_match('/"/', $value)) {
						$attrs .= ' '. strtolower($attribute) .'="'. $value .'"';
					} else {
						$attrs .= ' '. strtolower($attribute) ."='". $value ."'";
					}
				} else {
					$$attribute = $value;
				}
			}

			$check = (isset($checked) and $checked) ? ' checked="checked"' : null;
			
			if (isset($type)) {
				if ($type === "text") {
					$HTML = ' <input'. $attrs .' type="text" /> ';
				} elseif ($type === "password") {
					$HTML = ' <input'. $attrs .' type="password" /> ';
				} elseif ($type === "submit") {
					$HTML = ' <input'. $attrs .' type="submit" /> ';
				} elseif ($type === "button") {
					$HTML = ' <input'. $attrs .' type="button" /> ';
				} elseif ($type === "checkbox") {
					$HTML = ' <input'. $attrs .' type="checkbox"'. $check .'/> ';
				} elseif ($type === "radio") {
					$HTML = ' <input'. $attrs .' type="radio"'. $check .' /> ';
				} elseif ($type === "file") {
					$HTML = ' <input'. $attrs .' type="file" /> ';
				} elseif ($type === "hidden") {
					$HTML = ' <input'. $attrs .' type="hidden" /> ';
				} elseif ($type === "image") {
					$HTML = ' <input'. $attrs .' type="image" /> ';
				} elseif ($type === "reset") {
					$HTML = ' <input'. $attrs .' type="reset" /> ';
				} elseif ($type === "url") {
					$HTML = ' <input'. $attrs .' type="url" /> ';
				} elseif ($type === "email") {
					$HTML = ' <input'. $attrs .' type="email" /> ';
				} else {
					$HTML = ' <input'. $attrs .' type="text" /> ';
				}
			} else {
				$HTML = ' <input'. $attrs .' type="text" /> ';
			}

			if (isset($p) and $p and isset($field)) {
				$HTML = '<p><span class="field">&raquo; '. $field .'</span><br />'. $HTML .'</p>';
			} elseif (isset($p) and $p) {
				$HTML = '<p>'. $HTML .'</p>';
			} elseif (isset($field)) {
				$HTML = '<span class="field">&raquo; '. $field .'</span><br />'. $HTML .'';
			}

			return $HTML;
		} elseif ($attributes) {
			return ' <input name="'. $attributes .'" type="text" /> ';
		} else {
			return null;	
		}
	}
}

if (!function_exists("formLabel")) {
	function formLabel($for, $text, $br = true)
	{
		$HTML = "<label for=\"$for\">$text: </label>";
		
		if ($br == true) {
			$HTML .= "<br />";
		}
		
		return $HTML;
	}
}

if (!function_exists("formOpen")) {
	function formOpen($action = null, $class = "forms", $ID = null, $legend = null, $method = "post", $enctype = null)
	{	
		$ID = (isset($ID)) ? ' id="'. $ID .'"' : null;
		$legend = (isset($legend)) ? "<legend>$legend</legend>\n" : null;
		$action = (strstr($action, "http://")) ? $action : _get("webBase") ."/". $action;
		$enctype = (!is_null($enctype)) ? ' enctype="'. $enctype .'"' : null;
		$HTML = '<form'. $ID .' action="'. $action .'" method="'. $method .'" class="'. $class .'"'. $enctype .'>' . "\n\t";
		$HTML .= '<fieldset>' . "\n\t\t";
		$HTML .= $legend;
		return $HTML;
	}
}

if (!function_exists("formRadio")) {
	function formRadio($attributes, $options = false)
	{
		if (isset($attributes) and is_array($attributes)) {
			$attrs = null;
			
			foreach ($attributes as $attr => $value) {
				if ($attr !== "position" and $attr !== "text" and $attr !== "type" and $attr !== "p" and $attr !== "field" and $attr !== "checked") {
					$attrs .= ' '. strtolower($attr) .'="'. $value .'"';
				} else {
					$$attr = $value;
				}
			}
			
			if (is_array($options)) {
				$HTML = null;

				foreach ($options as $option) {
					if (is_array($option)) { 
						foreach ($option as $attribute) {
							if ($attribute["default"]) {
								$check = ' checked="checked"';
							} else {
								$check = null;	
							}

							$HTML .= ' <input '. $attrs .' value="'. $attribute["name"] .'" type="radio"'. $check .' />'. $attribute["value"];
						}					
					}	
				}
			} else {
				$check = (isset($checked) and $checked) ? ' checked="checked"' : null;

				if (isset($position) and $position === "left" and isset($text)) {
					$HTML = $text . ' <input'. $attrs .' type="radio"'. $check .' />';
				} elseif (isset($position) and $position === "right" and isset($text)) {
					$HTML = '<input'. $attrs .' type="radio"'. $check .' /> '. $text;
				} elseif (isset($text)) {
					$HTML = $text . ' <input'. $attrs .' type="radio"'. $check .' />';
				} else {
					$HTML = '<input'. $attrs .' type="radio"'. $check .' />';
				}	
			}
			
			if (isset($p) and isset($field)) {
				$HTML = '<p><span class="field">&raquo; '. $field .'</span><br />'. $HTML .'</p>';
			}

			return $HTML;
		} else {
			return null;
		}
	}	
}

if (!function_exists("formSelect")) {
	function formSelect($attributes = false, $options = false, $select = false)
	{
		if (isset($attributes) and is_array($attributes)) {
			$attrs = null;
			
			foreach ($attributes as $attribute => $value) {
				if ($attribute !== "p" and $attribute !== "field") {
					if ($attribute !== "disabled") {
						$attrs .= ' '. strtolower($attribute) .'="'. $value .'"';
					} elseif ($value) {
						$attrs .= ' disabled';
					}
				} else {
					$$attribute = $value;
				}
			}
			
			$HTML = '<select'. $attrs .' size="1">';
			
			if (is_array($options)) {
				foreach ($options as $option) {
					if ($select) {
						$HTML .= $select;						
						$select = false;
					}
					
					if (is_string($option)) {
						$HTML .= '<option>'. $option .'</option>';	
					} else {
						$selected = (isset($option["selected"]) and $option["selected"]) ? ' selected="selected"' : null;
						$value = (isset($option["value"])) ? $option["value"] : null;
						$text = (isset($option["option"])) ? $option["option"] : null;
						
						$HTML .= '<option value="'. $value .'"'. $selected .'>'. $text .'</option>';
					}			
				}
			}
			
			$HTML .= '</select>';
			
			unset($options);

			if (isset($p) and isset($field)) {
				$HTML = '<p><span class="field">&raquo; '. $field .'</span><br />'. $HTML .'</p>';
			}
			
			return $HTML;
		} else {
			return null;
		}
	}
}

if (!function_exists("formTextarea")) {
	function formTextarea($attributes = false)
	{
		if (isset($attributes) and is_array($attributes)) {
			$attrs = null;
			
			foreach ($attributes as $attribute => $val) {
				if ($attribute !== "type" and $attribute !== "value" and $attribute !== "p" and $attribute !== "field") {
					$attrs .= ' '. strtolower($attribute) .'="'. $val .'"';
				} else {
					$$attribute = $val;
				}
			}
			
			$value = isset($value) ? $value : null;
			$HTML = '<textarea'. $attrs .'>'. $value .'</textarea>';

			if (isset($p) and isset($field)) {
				$HTML = '<p><span class="field">&raquo; '. $field .'</span><br />'. $HTML .'</p>';
			}

			return $HTML;
		} else {
			return null;
		}								
	}
}

if (!function_exists("formAction")) {
	function formAction($action = null)
	{
		return '<p class="save-cancel">
					<input id="'. $action .'" name="'. $action .'" value="'. __(ucfirst($action)) .'" type="submit" class="btn btn-success">
					<input id="cancel" name="cancel" value="'. __("Cancel") .'" type="submit" class="btn btn-danger" />
				</p>';
	}
}

if (!function_exists("formCaptcha")) {	 
	function formCaptcha($attributes = false, $alphanumeric = false)
	{
		$hash = md5(getURL());
		$HTML = '<input type="hidden" name="captcha_token" value="'. $hash .'" />';
		$HTML .= '<input type="hidden" name="captcha_type" value="'. ($alphanumeric ? 'alphanumeric' : 'aritmethic') .'" />';
		
		if (!$alphanumeric) {
			$attributes["style"] = (isset($attributes["style"]) ? $attributes["style"] : '') ."max-width: 50px; text-align: center;";
			$attributes["type"]  = "number";

			$num1 = rand(1, 9);
			$num2 = rand(1, 9);

			switch(rand(1, 3)) {
				case 1:
					$operation = '-';
					$answer = $num1 - $num2;
					break;
				default:
					$operation = '+';
					$answer = $num1 + $num2;
			}

			$HTML .= __("How much is ") . (rand(0, 1) === 0 ? $num1 : num2str($num1, true)) .' '. $operation .' '. (rand(0, 1) === 0 ? $num2 : num2str($num2, true)) .'? ';
		} else {
			$attributes["style"] = (isset($attributes["style"]) ? $attributes["style"] : '') . "max-width: 200px; text-align: center;";

			$answer = "";
			$characters = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

			for ($i = 0; $i < 5; $i++) {
				$answer .= $characters[rand(0, count($characters) - 1)];
			}

			$HTML .= '<img src="'. path("captcha/$hash") .'" /><br />';
		}

		SESSION("ZanCaptcha". $hash, $answer);

		if (isset($attributes) and is_array($attributes)) {
			$attrs = null;
			
			foreach ($attributes as $attribute => $value) {
				if ($attribute === "required") {
					$attrs .= ' required ';
				} elseif ($attribute === "events") {
					$attrs .= ' '. $value .' ';
				} elseif ($attribute !== "p" and $attribute !== "field") {
					if (!preg_match('/"/', $value)) {
						$attrs .= ' '. strtolower($attribute) .'="'. $value .'"';
					} else {
						$attrs .= ' '. strtolower($attribute) ."='". $value ."'";
					}
				} else {
					$$attribute = $value;
				}
			}
			
			$HTML .= '<input'. $attrs .' type="text" /> ';

			if (isset($p) and $p and isset($field)) {
				$HTML = '<p><span class="field">&raquo; '. $field .'</span><br />'. $HTML .'</p>';
			} elseif (isset($p) and $p) {
				$HTML = '<p>'. $HTML .'</p>';
			} elseif (isset($field)) {
				$HTML = '<span class="field">&raquo; '. $field .'</span><br />'. $HTML .'';
			}

			return $HTML;
		} elseif ($attributes) {
			return $HTML .'<input name="'. $attributes .'" type="text" />';
		} else {
			return null;
		}
	}
}