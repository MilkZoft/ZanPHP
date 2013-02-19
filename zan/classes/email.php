<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

class ZP_Email extends ZP_Load
{
	public $email;
	public $fromEmail;
	public $fromName;
	public $message;
	public $subject;
	public $library = "PHPMailer";
	
	public function send()
	{
		if (strtolower($this->library) === "phpmailer") {
			$this->config("email");

			$this->PHPMailer = $this->library("phpmailer", "PHPMailer");

			$this->PHPMailer->isHTML(true);
			$this->PHPMailer->isSMTP();
			$this->PHPMailer->addAddress($this->email);
			$this->PHPMailer->FromName = $this->fromName;
			$this->PHPMailer->Subject = $this->subject;
			$this->PHPMailer->Body = $this->message;
			$this->PHPMailer->Host = GMAIL_SSL;
			$this->PHPMailer->Port = GMAIL_PORT;
			$this->PHPMailer->Username = GMAIL_USER;
			$this->PHPMailer->Password = GMAIL_PWD;
			$this->PHPMailer->SMTPAuth = true;

			if (!$this->PHPMailer->Send()) {
				return false;
			} else { 
				return true;
			}
		} else {
			$headers = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=utf-8\r\n";
			$headers .= "From: ". $this->fromName ." <". $this->fromEmail .">\r\n";
			
			if (!@mail($this->email, $this->subject, $this->message, $headers)) {
				return false;
			} else {
				return true;
			}
		}
	}
	
	public function setLibrary($library = "native")
	{
		$this->library = $library;
	}
}