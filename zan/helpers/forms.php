<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

/**
 * Forms Helper
 *
 *
 *
 * @package		ZanPHP
 * @subpackage	Core
 * @category	Helpers
 * @author		MilkZoft
 * @link		http://www.milkzoft.com
 */

/**
 * formCheckbox
 *
 * Sets a specific <input /> type Checkbox tag and its attributes
 *
 * @param string  $text     = NULL
 * @param string  $position = "Right"
 * @param string  $name
 * @param string  $value
 * @param string  $ID       = NULL
 * @param boolean $checked  = FALSE
 * @param string  $events   = NULL
 * @param boolean $disabled = FALSE
 * @return string value
 */
function formCheckbox($attributes = FALSE) {
	if(isset($attributes) and is_array($attributes)) {
	$attrs = NULL;

	foreach($attributes as $attribute => $value) {
		if($attribute !== "position" and $attribute !== "text" and $attribute !== "type" and $attribute !== "checked") {
		$attrs .= ' '. strtolower($attribute) .'="'. encode($value) .'"';
		} else {
		$$attribute = encode($value);
		}
	}

	if(isset($checked) and $checked) {
		$check = ' checked="checked"';
	} else {
		$check = NULL;
	}

	if(isset($position) and $position === "left" and isset($text)) {
		return $text .' <input'. $attrs .' type="checkbox"'. $check .' />';
	} elseif(isset($position) and $position === "right" and isset($text)) {
		return '<input'. $attrs .' type="checkbox"'. $check .' /> '. $text;
	} elseif(isset($text)) {
		return $text .' <input'. $attrs .' type="checkbox"'. $check .' />';
	} else {
		return '<input'. $attrs .' type="checkbox"'. $check .' />';
	}
	} else {
	return NULL;
	}
}

/**
 * formClose
 *
 * Closes a Basic Form structure
 *
 * @returns string $HTML
 */
function formClose() {
	$HTML  = "\t" . "</fieldset>" . "\n";
	$HTML .= "</form>";

	return $HTML;
}

function formField($a = NULL, $text, $raquo = TRUE) {
	$raquo = ($raquo === TRUE) ? "&raquo; " : "";

	if(!is_null($a)) {
	$HTML  = '<p class="field">' . "\n";
	$HTML .= "\t" . '<a '. $a .'>'. $raquo . $text .'</a>' . "\n";
	$HTML .= '</p>' . "\n";
	} else {
	$HTML  = '<p class="field">' . "\n";
	$HTML .= "\t" . $raquo . $text . "\n";
	$HTML .= '</p>' . "\n";
	}

	return $HTML;
}

/**
 * formInput
 *
 * Sets an <input /> tag with a custom attributes.
 *
 * @param mixed   $p      = "Yes"
 * @param string  $text   = NULL
 * @param string  $name   = NULL
 * @param string  $value  = NULL
 * @param string  $class  = "input"
 * @param string  $type   = "text"
 * @param string  $ID     = NULL
 * @param string  $events = NULL
 * @param string  $src    = NULL
 * @param boolean $raquo  = NULL
 * @returns string $HTML
 */
function formInput($attributes = FALSE) {
	if(isset($attributes) and is_array($attributes)) {
	$attrs = NULL;

	foreach($attributes as $attribute => $value) {
		if($attribute === "required") {
		$attrs .= ' required ';
		} elseif($attribute === "events") {
		$attrs .= ' '. $value .' ';
		} elseif($attribute !== "type" and $attribute !== "p" and $attribute !== "field") {
		if(!preg_match('/"/', $value)) {
			$attrs .= ' '. strtolower($attribute) .'="'. $value .'"';
		} else {
			$attrs .= ' '. strtolower($attribute) ."='". $value ."'";
		}
		} else {
		$$attribute = $value;
		}
	}

	if(isset($type)) {
		if($type === "text") {
		$HTML = '<input'. $attrs .' type="text" /> ' . "\n";
		} elseif($type === "password") {
		$HTML = '<input'. $attrs .' type="password" /> ' . "\n";
		} elseif($type === "submit") {
		$HTML = '<input'. $attrs .' type="submit" /> ' . "\n";
		} elseif($type === "button") {
		$HTML = '<input'. $attrs .' type="button" /> ' . "\n";
		} elseif($type === "checkbox") {
		$HTML = '<input'. $attrs .' type="checkbox" /> ' . "\n";
		} elseif($type === "radio") {
		$HTML = '<input'. $attrs .' type="radio" /> ' . "\n";
		} elseif($type === "file") {
		$HTML = '<input'. $attrs .' type="file" /> ' . "\n";
		} elseif($type === "hidden") {
		$HTML = '<input'. $attrs .' type="hidden" /> ' . "\n";
		} elseif($type === "image") {
		$HTML = '<input'. $attrs .' type="image" /> ' . "\n";
		} elseif($type === "reset") {
		$HTML = '<input'. $attrs .' type="reset" /> ' . "\n";
		} elseif($type === "url") {
		$HTML = '<input'. $attrs .' type="url" /> ' . "\n";
		} else {
		$HTML = '<input'. $attrs .' type="text" /> ' . "\n";
		}
	} else {
		$HTML = '<input'. $attrs .' type="text" /> ' . "\n";
	}

	if(isset($p) and $p and isset($field)) {
		$HTML = '	<p>
				<span class="field">&raquo; '. $field .'</span><br />
				'. $HTML .'
			</p>';
	} elseif(isset($p) and $p) {
		$HTML = '	<p>
				'. $HTML .'
			</p>';
	} elseif(isset($field)) {
		$HTML = '<span class="field">&raquo; '. $field .'</span><br />'. $HTML .'';
	}

	return $HTML;
	} elseif($attributes) {
	return '<input name="'. $attributes .'" type="text" />' . "\n";
	} else {
	return NULL;
	}
}

/**
 * formLabel
 *
 * Sets a <label> tag.
 *
 * @param string  $for
 * @param string  $value
 * @param boolean $br = TRUE
 * @returns string $HTML
 */
function formLabel($for, $text, $br = TRUE) {
	$HTML = "<label for=\"$for\">$text: </label>";

	if($br == TRUE) {
	$HTML .= "<br />" . "\n";
	}

	return $HTML;
}

/**
 * formOpen
 *
 * Sets and Opens a basic form structure
 *
 * @param string $ID      = NULL
 * @param string $text    = NULL
 * @param string $action
 * @param string $class   = "forms"
 * @param string $method  = "post"
 * @param string $enctype = "multipart/form-data"
 * @returns string $HTML
 */
function formOpen($action = NULL, $class = "forms", $ID = NULL, $legend = NULL, $method = "post", $enctype = NULL) {
	$ID      = (isset($ID))     ? ' id="'. $ID .'"' 			  			 : NULL;
	$legend  = (isset($legend)) ? "<legend>$legend</legend>" . "\n" : NULL;
	$action  = (strstr($action, "http://")) ? $action : _get("webBase") . "/" . $action;
	$enctype = (!is_null($enctype)) ? ' enctype="'. $enctype .'"' : NULL;

	$HTML  = '<form'. $ID .' action="'. $action .'" method="'. $method .'" class="'. $class .'"'. $enctype .'>' . "\n\t";
	$HTML .= '<fieldset>' . "\n\t\t";
	$HTML .= $legend . "\n";

	return $HTML;
}

/**
 * formRadio
 *
 * Sets a <input /> type Radio tag and its attributes
 *
 * @param string  $text     = NULL
 * @param string  $position = "Right"
 * @param string  $name
 * @param string  $value
 * @param string  $ID       = NULL
 * @param boolean $checked  = FALSE
 * @param string  $events   = NULL
 * @param boolean $disable  = FALSE
 * @returns string value
 */
function formRadio($attributes, $options = FALSE) {
	if(isset($attributes) and is_array($attributes)) {
	$attrs = NULL;

	foreach($attributes as $attr => $value) {
		if($attr !== "position" and $attr !== "text" and $attr !== "type" and $attr !== "p" and $attr !== "field" and $attr !== "checked") {
		$attrs .= ' '. strtolower($attr) .'="'. $value .'"';
		} else {
		$$attr = $value;
		}
	}

	if(is_array($options)) {
		$HTML = NULL;

		foreach($options as $option) {
		if(is_array($option)) {
			foreach($option as $attribute) {
			if($attribute["default"]) {
				$check = ' checked="checked"';
			} else {
				$check = NULL;
			}

			$HTML .= ' <input '. $attrs .' value="'. $attribute["name"] .'" type="radio"'. $check .' />'. $attribute["value"];
			}
		}
		}
	} else {
		if(isset($checked) and $checked) {
		$check = ' checked="checked"';
		} else {
		$check = NULL;
		}

		if(isset($position) and $position === "left" and isset($text)) {
		$HTML = $text . ' <input'. $attrs .' type="radio"'. $check .' />';
		} elseif(isset($position) and $position === "right" and isset($text)) {
		$HTML = '<input'. $attrs .' type="radio"'. $check .' /> '. $text;
		} elseif(isset($text)) {
		$HTML = $text . ' <input'. $attrs .' type="radio"'. $check .' />';
		} else {
		$HTML = '<input'. $attrs .' type="radio"'. $check .' />';
		}
	}

	if(isset($p) and isset($field)) {
		$HTML = '	<p>
				<span class="field">&raquo; '. $field .'</span><br />
				'. $HTML .'
			</p>';

	}

	return $HTML;
	} else {
	return NULL;
	}
}

/**
 * formSelect
 *
 * Sets a <select> tag and its attributes
 *
 * @param boolean $p        = TRUE
 * @param string  $text
 * @param string  $name
 * @param mixed   $options
 * @param string  $class    = "Select"
 * @param string  $selected = NULL
 * @param string  $ID       = NULL
 * @param string  $size     = "1"
 * @param boolean $raquo    = TRUE
 * @returns string $HTML
 */
function formSelect($attributes = FALSE, $options = FALSE, $select = FALSE) {
	if(isset($attributes) and is_array($attributes)) {
	$attrs = NULL;

	foreach($attributes as $attribute => $value) {
		if($attribute !== "p" and $attribute !== "field") {
		$attrs .= ' '. strtolower($attribute) .'="'. $value .'"';
		} else {
		$$attribute = $value;
		}
	}

	$HTML = "\t" . '<select'. $attrs .' size="1">'. "\n";

	if(is_array($options)) {
		foreach($options as $option) {
		if($select) {
			$HTML .= $select;

			$select = FALSE;
		}

		if(is_string($option)) {
			$HTML .= "\t\t" . '<option>'. $option .'</option>' . "\n";
		} else {
			$selected = (isset($option["selected"]) and $option["selected"]) ? ' selected="selected"' : NULL;
			$value    = (isset($option["value"]))  ? $option["value"]  : NULL;
			$text 	  = (isset($option["option"])) ? $option["option"] : NULL;

			$HTML .= "\t\t" . '<option value="' . $value . '"' . $selected . '>' . $text . '</option>' . "\n";
		}
		}
	}

	$HTML .= "\t" . '</select>' . "\n";

	unset($options);

	if(isset($p) and isset($field)) {
		$HTML = '	<p>
				<span class="field">&raquo; '. $field .'</span><br />
				'. $HTML .'
			</p>';

	}

	return $HTML;
	} else {
	return NULL;
	}
}

/**
 * formTextarea
 *
 * Sets a <textarea> tag and its attributes
 *
 * @param boolean $p        = TRUE
 * @param string  $text
 * @param string  $name
 * @param string  $value    = NULL
 * @param string  $class    = "textarea"
 * @param string  $selected = NULL
 * @param string  $ID       = NULL
 * @param int     $rows     = 25
 * @param int     $cols     = 90
 * @param boolean $raquo    = TRUE
 * @returns string $HTML
 */
function formTextarea($attributes = FALSE) {
	if(isset($attributes) and is_array($attributes)) {
	$attrs = NULL;

	foreach($attributes as $attribute => $val) {
		if($attribute !== "type" and $attribute !== "value" and $attribute !== "p" and $attribute !== "field") {
		$attrs .= ' '. strtolower($attribute) .'="'. $val .'"';
		} else {
		$$attribute = $val;
		}
	}

	$value = isset($value) ? $value : NULL;

	$HTML = '<textarea'. $attrs .'>'. $value .'</textarea>';

	if(isset($p) and isset($field)) {
		$HTML = '	<p>
				<span class="field">&raquo; '. $field .'</span><br />
				'. $HTML .'
			</p>';

	}

	return $HTML;
	} else {
	return NULL;
	}
}

function formSave($action = NULL) {
	$HTML = '
	<p class="save-cancel">
		<input id="'. $action .'" name="'. $action .'" value="'. __(ucfirst($action)) .'" type="submit" class="btn btn-success">
		<input id="cancel" name="cancel" value="'. __("Cancel") .'" type="submit" class="btn btn-danger" />
	</p>';

	return $HTML;
}

/**
 * formCaptcha
 *
 * Generate a captcha to validate forms
 *
 * @param array   $attributes   = [$p, $field, $name, $class, ...]
 * @param boolean $alphanumeric = "No"
 * @returns string $HTML
 */
function formCaptcha($attributes = FALSE, $alphanumeric = FALSE) {
	$hash = md5(getURL());
	$HTML = '<input type="hidden" name="captcha_token" value="'. $hash .'" />';
	$HTML .= '<input type="hidden" name="captcha_type" value="'. ($alphanumeric ? 'alphanumeric' : 'aritmethic') .'" />';

	if(!$alphanumeric) {
	$attributes["style"] = (isset($attributes["style"]) ? $attributes["style"] : '') . "max-width: 50px; text-align: center;";
	$attributes["type"]  = "number";

	$num1 = rand(1, 9);
	$num2 = rand(1, 9);

	switch(rand(1, 3)) {
		case 1:
		$operation = '-';
		$answer    = $num1 - $num2;
		break;

		default:
		$operation = '+';
		$answer    = $num1 + $num2;
	}

	$HTML .= __("How much is ") . (rand(0, 1) === 0 ? $num1 : num2str($num1, TRUE)) .' '. $operation .' '. (rand(0, 1) === 0 ? $num2 : num2str($num2, TRUE)) .'? ';

	} else {
	$attributes["style"] = (isset($attributes["style"]) ? $attributes["style"] : '') . "max-width: 200px; text-align: center;";

	$answer 	= "";
	$characters = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
				"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z",
				"1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

	for($i = 0; $i < 5; $i++) {
		$answer .= $characters[rand(0, count($characters) - 1)];
	}

	$HTML .= '<img src="'. path("captcha/$hash") .'" /><br />';
	}

	SESSION("ZanCaptcha$hash", $answer);

	if(isset($attributes) and is_array($attributes)) {
	$attrs = NULL;

	foreach($attributes as $attribute => $value) {
		if($attribute === "required") {
		$attrs .= ' required ';
		} elseif($attribute === "events") {
		$attrs .= ' '. $value .' ';
		} elseif($attribute !== "p" and $attribute !== "field") {
		if(!preg_match('/"/', $value)) {
			$attrs .= ' '. strtolower($attribute) .'="'. $value .'"';
		} else {
			$attrs .= ' '. strtolower($attribute) ."='". $value ."'";
		}
		} else {
		$$attribute = $value;
		}
	}

	$HTML .= '<input'. $attrs .' type="text" /> ' . "\n";

	if(isset($p) and $p and isset($field)) {
		$HTML = '	<p>
				<span class="field">&raquo; '. $field .'</span><br />
				'. $HTML .'
			</p>';
	} elseif(isset($p) and $p) {
		$HTML = '	<p>
				'. $HTML .'
			</p>';
	} elseif(isset($field)) {
		$HTML = '<span class="field">&raquo; '. $field .'</span><br />'. $HTML .'';
	}

	return $HTML;
	} elseif($attributes) {
	return $HTML .'<input name="'. $attributes .'" type="text" />' . "\n";
	} else {
	return NULL;
	}
}