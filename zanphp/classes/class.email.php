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
 * Access from index.php
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

/**
 * ZanPHP Email Class
 *
 * This class allows to manipulate emails
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	classes
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/classes/email_class
 */
class ZP_Email extends ZP_Load {
  
	/*
	 * 
	 * 
	 * Contains the Receiver's email string
	 * 
	 * @var public $fromName
	 */
	public $email;
	
	/*
	 * 
	 * 
	 * Contains the email string of the Sender
	 * 
	 * @var public $fromName
	 */
	public $fromEmail;
	
    /*
	 * Contains the name string of the Sender
	 * 
	 * @var public $fromName
	 */
	public $fromName;
	
	/*
	 * 
	 * 
	 * Contains the email's message
	 * 
	 * @var public $fromName
	 */
	public $message;
	
	/*
	 * 
	 * 
	 * Contains the email's subject
	 * 
	 * @var public $fromName
	 */
	public $subject;
	
	/*
	 * send
	 * 
	 * Sends an email
	 *  
	 * @return @mixed
	*/
	public function send() {
		if($this->library === "PHPMailer") {
			$this->library("class.phpmailer");
			
			$this->PHPMailer = new PHPMailer();

			$this->PHPMailer->isHTML(TRUE);		
			$this->PHPMailer->isSMTP();
			$this->PHPMailer->addAddress($this->email);

			$this->PHPMailer->FromName = $this->fromName;
			$this->PHPMailer->Subject  = $this->subject;
			$this->PHPMailer->Body     = $this->message;
			$this->PHPMailer->Host 	   = _gSSL;
			$this->PHPMailer->Port 	   = _gPort;
			$this->PHPMailer->Username = _gUser;
			$this->PHPMailer->Password = _gPwd;
			$this->PHPMailer->SMTPAuth = TRUE;
			
			if($this->PHPMailer->Send() === FALSE) {
				return FALSE;
			} else { 
				return TRUE;
			}		
		} else {
			$headers  = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=utf-8\r\n";
			$headers .= "From: " . $this->fromName . " <" . $this->fromEmail . ">\r\n";			
			
			if(@mail($this->email, $this->subject, $this->message, $headers) === FALSE) {
				return FALSE;
			} else {
				return TRUE;
			}		
		}
	}
	
	/*
	 * setLibrary
	 * 
	 * Sets the way the email will be send (optionally the PHP-defined mail() function or with a external Library)
	 *  
	 * @param string $library = "native"
	 * @return @void
	*/
	public function setLibrary($library = "native") {
		$this->library = $library;
	}
}
