<?php
require_once dirname(__FILE__)."/../../PHPMailer/class.phpmailer.php";
/**
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 *
 *	   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * LoggerAppenderMailEvent appends individual log events via email.
 * 
 * This appender is similar to LoggerAppenderMail, except that it sends each 
 * each log event in an individual email message at the time when it occurs.
 * 
 * This appender uses a layout.
 * 
 * ## Configurable parameters: ##
 * 
 * - **to** - Email address(es) to which the log will be sent. Multiple email
 *     addresses may be specified by separating them with a comma.
 * - **from** - Email address which will be used in the From field.
 * - **subject** - Subject of the email message.
 * - **smtpHost** - Used to override the SMTP server. Only works on Windows.
 * - **port** - Used to override the default SMTP server port. Only works on 
 *     Windows.
 *
 * @version $Revision: 1343601 $
 * @package log4php
 * @subpackage appenders
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @link http://logging.apache.org/log4php/docs/appenders/mail-event.html Appender documentation
 */
class LoggerAppenderMailEvent2 extends LoggerAppender {

	/** 
	 * Email address to put in From field of the email.
	 * @var string
	 */
	protected $from;

	/** 
	 * Mail server port (widnows only).
	 * @var integer 
	 */
	protected $port = 25;

	/** 
	 * Mail server hostname (windows only).
	 * @var string   
	 */
	protected $smtpHost;

	/** 
	 * The subject of the email.
	 * @var string
	 */
	protected $subject = 'Log4php Report';

	/**
	 * One or more comma separated email addresses to which to send the email. 
	 * @var string
	 */
	protected $to = null;
	
	/** 
	 * Indiciates whether this appender should run in dry mode.
	 * @deprecated
	 * @var boolean 
	 */
	protected $dry = false;

	protected $authentication = false;

	protected $username = false;
	protected $password = false;

	private $mailer;
	
	public function activateOptions() {
		$this->mailer = new PHPMailer();
		$this->mailer->IsSMTP(true);            // use SMTP

		//$mailer->SMTPDebug  = 2;        // enables SMTP debug information (for testing)
		// 1 = errors and messages
		// 2 = messages only
		$this->mailer->Host       = $this->smtpHost;
		$this->mailer->Port       = $this->port;                    // set the SMTP port
		
		if ($this->username && $this->password){
			$this->mailer->SMTPAuth   = true;                  // enable SMTP authentication
			$this->mailer->Username   = $this->username;
			$this->mailer->Password   = $this->password;
		}
		$this->mailer->SMTPDebug = 1;
		$this->mailer->Subject = $this->subject;
		if ($this->fromName){
			$this->mailer->SetFrom($this->from, $this->fromName);		
		}
		else{
			$this->mailer->From = $this->from;
		}
		$this->mailer->CharSet = 'UTF-8';
		$this->mailer->AddAddress($this->to);
		
		$this->closed = false;
	}

	public function append(LoggerLoggingEvent $event) {
		$msg = $this->layout->getHeader() . $this->layout->format($event) . $this->layout->getFooter($event);
 		$this->mailer->MsgHTML($msg);

		if ($this->mailer->Send()){
			//echo "Mail sent ($msg)\n";
		}
		else{
			error_log("Unable to send email " .$this->mailer->ErrorInfo);
		}
	}
	
	/** Sets the 'from' parameter. */
	public function setFrom($from) {
		$this->setString('from', $from);
	}
	
	/** Returns the 'from' parameter. */
	public function getFrom() {
		return $this->from;
	}


	/** Sets the 'fromName' parameter. */
	public function setFromName($fromName) {
		$this->setString('fromName', $fromName);
	}
	
	/** Returns the 'fromName' parameter. */
	public function getFromName() {
		return $this->fromName;
	}


	/** Sets the 'username' parameter. */
	public function setUsername($username) {
		$this->setString('username', $username);
	}
	
	/** Returns the 'username' parameter. */
	public function getUsername() {
		return $this->username;
	}


		/** Sets the 'password' parameter. */
	public function setPassword($password) {
		$this->setString('password', $password);
	}
	
	/** Returns the 'password' parameter. */
	public function getPassword() {
		return $this->password;
	}
	
	/** Sets the 'port' parameter. */
	public function setPort($port) {
		$this->setPositiveInteger('port', $port);
	}
	
	/** Returns the 'port' parameter. */
	public function getPort() {
		return $this->port;
	}
	
	/** Sets the 'smtpHost' parameter. */
	public function setSmtpHost($smtpHost) {
		$this->setString('smtpHost', $smtpHost);
	}
	
	/** Returns the 'smtpHost' parameter. */
	public function getSmtpHost() {
		return $this->smtpHost;
	}
	
	/** Sets the 'subject' parameter. */
	public function setSubject($subject) {
		$this->setString('subject',  $subject);
	}
	
	/** Returns the 'subject' parameter. */
	public function getSubject() {
		return $this->subject;
	}
	
	/** Sets the 'to' parameter. */
	public function setTo($to) {
		$this->setString('to',  $to);
	}
	
	/** Returns the 'to' parameter. */
	public function getTo() {
		return $this->to;
	}

	/** Enables or disables dry mode. */
	public function setDry($dry) {
		$this->setBoolean('dry', $dry);
	}
}
