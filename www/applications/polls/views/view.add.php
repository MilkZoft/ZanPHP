<?php 
	if(!defined("_access")) {
		die("Error: You don't have permission to access here..."); 
	}

	print div("add-form", "class");
		print formOpen($href, "form-add", "form-add");
			print p(__(ucfirst(whichApplication())), "resalt");
			
			print isset($alert) ? $alert : NULL;

			print formInput(array("name" => "title", "class" => "required", "field" => __("Question"), "p" => TRUE, "value" => $title));
						
			print div("answers");
				print formField(NULL, __("Answers") ." (". __("Empty answers not be added") . ")");
				
				if(is_array($answers)) { 
					foreach($answers as $key => $answer) { 
						print p(TRUE, "field panswer");	
							print span("count", ($key + 1) . ".-");
							print formInput(array("name" => "answers[]", "class" => "input required", "value" => $answer));	
						print p(FALSE);
					}
				} else { 
					print formInput(array("name" => "answers[]", "class" => "required", "value" => $answers, "p" => TRUE));
					print formInput(array("name" => "answers[]", "class" => "required", "value" => $answers, "p" => TRUE));
					print formInput(array("name" => "answers[]", "class" => "required", "value" => $answers, "p" => TRUE));
					print formInput(array("name" => "answers[]", "class" => "required", "value" => $answers, "p" => TRUE));
					print formInput(array("name" => "answers[]", "class" => "required", "value" => $answers, "p" => TRUE));	
				} 

			print div(FALSE);
			
			print span(NULL, repeat("&nbsp;", 4), "addImg");

			$options = array(
				0 => array("value" => "Simple",   "option" => __("Simple"),   "selected" => ($type === "Simple")   ? TRUE : FALSE),
			);

			print formSelect(array("name" => "type", "class" => "select", "p" => TRUE, "field" => __("Type")), $options);
			
			$options = array(
				0 => array("value" => "Active",   "option" => __("Active"),   "selected" => ($situation === "Active")   ? TRUE : FALSE),
				1 => array("value" => "Inactive", "option" => __("Inactive"), "selected" => ($situation === "Inactive") ? TRUE : FALSE)
			);

			print formSelect(array("name" => "situation", "class" => "select", "p" => TRUE, "field" => __("Situation")), $options);
			
			print formSave($action);
			
			print formInput(array("name" => "ID", "type" => "hidden", "value" => $ID));
		print formClose();
	print div(FALSE);