<?php
	if(!defined("_access")) {
		die("Error: You don't have permission to access here...");
	}
	
	print div("add-form", "class"); //<div class="add-form">
		print formOpen($href, "form-add");
			print p(__(ucfirst(whichApplication())), "resalt");
			
			print isset($alert["alert"]) ? $alert["alert"] : NULL;
			
			print formInput(array("name" => "title", "class" => "input required", "field" => __("Title"), "p" => TRUE, "value" => $title));
			
			print formTextarea(array("name" => "content", "class" => "textarea", "field" => __("Content"), "p" => TRUE, "value" => $content));
			
			#print formField(NULL, __("Languages") . "<br />" . getLanguagesRadios($language));
			
			$options = array(
				0	=> array(
							"value"    => "Active",
							"option"   => __("Active"),
							"selected" => ($situation === "Active") ? TRUE : FALSE
				),

				1	=> array(
							"value"    => "Inactive",
							"option"   => __("Inative"),
							"selected" => ($situation === "Inactive") ? TRUE : FALSE
				),
			);
			
			print formSelect(array("name" => "situation", "class" => "select", "p" => TRUE, "field" => __("Situation")), $options);
			
			$options = array(
				0	=> array(
							"value"    => "1",
							"option"   => __("Yes"),
							"selected" => ((int) $principal === 1) ? TRUE : FALSE
				),

				1	=> array(
							"value"    => "0",
							"option"   => __("No"),
							"selected" => ((int) $principal === 0) ? TRUE : FALSE
				),
			);
			
			print formSelect(array("name" => "principal", "class" => "select", "p" => TRUE, "field" => __("Principal")), $options);
			
			print formSave($action);
			
			print formInput(array("name" => "ID", "type" => "hidden", "value" => $ID));
		print formClose();
	print div(FALSE);		
			
			
			