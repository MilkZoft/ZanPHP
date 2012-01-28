<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Polls_Model extends ZP_Model {
	
	public function __construct() {
		$this->Db = $this->db();
		
		$this->helper(array("time", "alerts", "router"));
		
		$this->table = "polls";
		
		$this->Data = $this->core("Data");
	}
	
	public function cpanel($action, $limit = NULL, $order = "ID_Poll DESC", $search = NULL, $field = NULL, $trash = FALSE) {	
		if($action === "edit" or $action === "save") {
			$validation = $this->editOrSave();
			
			if($validation) {
				return $validation;
			}
		}
		
		if($action === "all") {
			return $this->all($trash, $order, $limit);
		} elseif($action === "edit") {
			return $this->edit();															
		} elseif($action === "save") {
			return $this->save();
		} elseif($action === "search") {
			return $this->search($search, $field);
		}
	}
	
	private function all($trash, $order, $limit) {
		if(!$trash) {
			if(SESSION("ZanUserPrivilege") === _super) {
				$data = $this->Db->findBySQL("Situation != 'Deleted'", $this->table, NULL, $order, $limit);
			} else {
				$data = $this->Db->findBySQL("ID_User = '".$_SESSION["ZanAdminID"]."' AND Situation != 'Deleted'", $this->table, NULL, $order, $limit);
			}	
		} else {
			if(SESSION("ZanUserPrivilege") === _super) {
				$data = $this->Db->findBy("Situation", "Deleted", $this->table, NULL, $order, $limit);
			} else {
				$data = $this->Db->findBySQL("ID_User = '". SESSION("ZanAdminID") ."' AND Situation = 'Deleted'", $this->table, NULL, $order, $limit);
			}
		}
		
		return $data;	
	}
	
	private function editOrSave() {		
		$j = 0;
		$k = 0;
		
		foreach(POST("answers") as $key => $answer) {
			if($answer === "") {
				$j += 1; 
			} else {
				$k += 1;
			}
		}
		
		if(count(POST("answers")) === $j) {
			return getAlert("You need to write a answers");
		} elseif($k < 2) {
			return getAlert("You need to write more than one answer");
		} else {
			$this->answers = POST("answers");
		}

		$validations = array(
			"title" => "required"
		);

		$this->Data->ignore("answers");
		
		$this->data = $this->Data->proccess(NULL, $validations);
	}
	
	private function save() {
		$lastID = $this->Db->insert($this->table, $this->data);
		
		if($lastID) {
			for($i = 0; $i <= count($this->answers) - 1; $i++) {
				if($this->answers[$i] !== "") {
					$data[$i]["ID_Poll"] = $lastID;
					$data[$i]["Answer"]  = decode($this->answers[$i]);
				}
			}
			
			$this->Db->insertBatch("polls_answers", $data);
			
			unsetSessions(FALSE);

			return getAlert("The poll has been saved correctly", "success");
		}
		
		return getAlert("Insert error");
	}

	public function delete($ID) {
		#$this->Db->deleteBy("ID_Poll", $ID, "polls_ips");
		#$this->Db->deleteBy("ID_Poll", $ID, "polls_answers");
		$this->Db->delete($ID, $this->table);

		showAlert("Poll deleted correctly", path("polls/cpanel/results"));
	}
	
	private function edit() {
		$this->Db->update($this->table, $this->data, POST("ID"));
		
		$this->Db->deleteBySQL("ID_Poll = '". POST("ID") ."'", "polls_answers");
		
		for($i = 0; $i <= count($this->answers) - 1; $i++) {
			if($this->answers[$i] !== "") {
				$data[$i]["ID_Poll"] = POST("ID");
				$data[$i]["Answer"]  = decode($this->answers[$i]);
			}
		}
			
		$this->Db->insertBatch("polls_answers", $data);
		
		return getAlert("The poll has been edit correctly", "success");
	}
	
	public function getByID($ID) {			
		$data  = $this->Db->find($ID, $this->table);

		$data1 = $this->Db->findBy("ID_Poll", $ID, "polls_answers");
		
		if($data1) {
			foreach($data1 as $answer) {
				$data[1][] = decode($answer["Answer"]);
			}
		}
	
		return $data;
	}
	
	public function getLastPoll() {		
		$data1 = $this->Db->findLast($this->table);

		if($data1) {			
			$data2 = $this->Db->findBy("ID_Poll", $data1[0]["ID_Poll"], "polls_answers");
			
			$data["question"] = $data1[0];
			$data["answers"]  = $data2;
			
			return $data;
		} else {
			return FALSE;
		}
	}

	public function getAllPolls() {
		$data = $this->Db->findAll($this->table);

		return $data;
	}
	
	public function vote() {
		$ID_Poll   = POST("ID_Poll");
		$ID_Answer = POST("answer");
		$IP		   = getIP();
		$date	   = now(4);
		$end	   = $date + 3600;
		
		$data = $this->Db->findBySQL("ID_Poll = '$ID_Poll' AND IP = '$IP'", "polls_ips");
		
		if($data) {
			showAlert("You've previously voted on this poll", _webBase);
		} else {			
			$this->Db->table("polls_answers");
			
			$values = "Votes = (Votes) + 1";
			
			$this->Db->values($values);								
			$this->Db->save($ID_Answer);

			$data = array(
				"ID_Poll" => $ID_Poll,
				"IP"	  => $IP
			);
			
			$this->Db->insert("polls_ips", $data);

			SESSION("ZanPoll", $ID_Poll);
		}
		
		return TRUE;
	}
}