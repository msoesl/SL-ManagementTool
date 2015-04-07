<?php

/**
 * 
 * Email Model is responsible to send emails.
 * @author almatroudis
 *
 */
class EmailModel{

	/**
	 * Receivers of the email
	 * @var String
	 */
	private $to;
	/**
	 * 
	 * Subject of the email
	 * @var String
	 */
	private $subject;
	/**
	 * The email's sender
	 */
	private $from;
	/**
	 * 
	 * The body of the Email
	 * @var String
	 */
	private $message;
	/**
	 * 
	 * Construct the email object
	 * @param String $to receiver of the emial
	 * @param String $subject	subject of the email
	 * @param String $message	the body of the email
	 * @param String $from	the email's sender
	 */
	public function __construct($to, $subject, $message, $from){
		// Set the variables
		$this->to=$to;
		$this->subject=$subject;
		$this->message = $message;
		$this->from = $from;
	}
	/**
	 * Send the email
	 */
	public function sendEmail(){
		// Set the headers such as cc, bcc and from.
		$headers  = 'From: ' .$this->from  . "\r\n" .
            'Reply-To: ' .$this->from  . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
		// Send the email.
		return mail($this->to, $this->subject, $this->message, $headers);
	}
}