<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Pages_Model extends ZP_Model {
	
	public function __construct() {
		$this->Db = $this->db();
		$this->Data = $this->core("Data");
		
		$this->language = whichLanguage();
		
		$this->table = "pages";
	}
	
	public function delete($ID) {
		 return $this->Db->delete($ID, $this->table);
	}
	
	public function edit($ID) {
		$validations = array(
			"title"   => "required",
			"content" => "required"
		);
		
		$data = array(
			"Slug"       => slug(POST("title", "clean")),
			"Language"   => whichLanguage(),
			"Start_Date" => now(4),
			"Text_Date"  => now(2),
		);
		
		$data = $this->Data->proccess($data, $validations);
		
		if(isset($data["error"])) {
			return array("error" => TRUE, "alert" => $data["error"]);
		} 
		
		$update = $this->Db->update($this->table, $data, $ID);
	
		return array("alert" => getAlert("The page has been edited correctly", "success"));
	}
	
	public function saveData() {
		$validations = array(
			"title"   => "required",
			"content" => "required"
		);
		
		$data = array(
			"Slug"       => slug(POST("title", "clean")),
			"Language"   => whichLanguage(),
			"Start_Date" => now(4),
			"Text_Date"  => now(2),
		);
		
		$data = $this->Data->proccess($data, $validations);
		
		if(isset($data["error"])) {
			return array("error" => TRUE, "alert" => $data["error"]);
		} 
		
		$this->Db->insert($this->table, $data);
		
		return array("alert" => getAlert("The page has been saved correctly", "success"));
	}
	
	public function save() {
		$title      = POST("title");
		$slug       = slug(POST("title", "clean"));
		$content    = POST("content");
		$situation  = POST("situation");
		$principal  = POST("principal");
		$language   = whichLanguage();
		$start_date = now(4);
		$text_date  = now(2);
			
		$data = array(
			"Title"      => $title,
			"Slug"       => $slug,
			"Content"    => $content,
			"Language"   => $language,
			"Principal"  => $principal,
			"Start_Date" => $start_date,
			"Text_Date"  => $text_date,
			"Situation"  => $situation	
		);
			
		$lastID = $this->Db->insert($this->table, $data);
		____($lastID);
	}
	
	public function getByID($ID) {
		$data = $this->Db->find($ID, $this->table);
		
		return $data;
	}
	
	public function getPrincipal() {		
		$data = $this->Db->findBySQL("Principal = 1 AND Language = '$this->language'", $this->table);
		
		return $data;
	}
	
	public function getPageBySlug($slug) {
		$this->Db->table($this->table);
		
		$data = $this->Db->findBySQL("Slug = '$slug' AND Language = '$this->language'");
		
		return $data;
	}
}