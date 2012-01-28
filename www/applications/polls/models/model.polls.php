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
					$answers[$i]["ID_Poll"] = $lastID;
					$answers[$i]["Answer"]  = decode($this->answers[$i]);
				}
			}
			
			$this->Db->insertBatch("polls_answers", $answers);
			
			unsetSessions(FALSE);

			return getAlert("The poll has been saved correctly", "success");
		}
		
		return getAlert("Insert error");
	}
	
	private function edit() {
		$this->Db->table($this->table);
		
		$this->Db->values("Title = '$this->title', Type = '$this->type', State = '$this->state'");								
		$this->Db->save($this->ID);
		
		$this->Db->table("polls_answers");
		$this->Db->deleteBySQL("ID_Poll = '$this->ID'");
		
		$this->Db->table("polls_answers", "ID_Poll, Answer");
		
		foreach($this->answers as $key => $answer) {
			if($answer !== "") {
				$this->Db->values("'$this->ID', '$answer'");
				$this->Db->save();
			}
		}
		
		return getAlert("The poll has been edit correctly", "success");
	}
	
	public function getByID($ID) {
		$this->Db->table($this->table);
		
		$data = $this->Db->find($ID);
		
		$this->Db->table("polls_answers", "Answer");
		
		$data2 = $this->Db->findBy("ID_Poll", $ID);
		
		if($data2) {
			foreach($data2 as $answer) {
				$data[1][] = $answer["Answer"];
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
			
			$values  = "Votes = (Votes) + 1";
			
			$this->Db->values($values);								
			$this->Db->save($ID_Answer);

			$data = array(
				"ID_Poll" => $ID_Poll,
				"IP"	  => $IP
			);
			
			$this->Db->insert("polls_ips", $data);

			SESSION("ZanPoll", $ID_Poll);
			
			showAlert("Thank you for your vote!", _webBase);
		}
		
		return TRUE;
	}
}