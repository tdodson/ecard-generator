<?php
	function send_email() {
		global $friendfirst;
		global $toemail;
		global $greeting;
		global $fromemail;
		global $firstname;
		global $html_email;

		$to_name = $friendfirst;
		$to = $toemail;
		$subject = "A Holiday Card from . . . ";
		$message = $greeting;
		$message = wordwrap($message, 70);
		$from_name = $firstname;
		$from = $fromemail;

		// PHP SMTP Mail Object
		$mail = new PHPMailer();
	 	// $mail->SMTPDebug  = 2; 	
		$mail->IsHTML(true);
		$mail->IsSMTP();
		$mail->Host     = "yourserver.something";
		$mail->Port     = 25;
		$mail->SMTPAuth = false; 
		$mail->FromName = $from_name;
		$mail->From     = $from;
		$mail->AddAddress($to, $to_name);
		$mail->Subject  = $subject;
		$mail->Body     = $html_email;

		$result = $mail->Send();
		// echo $result ? 'Sent' : 'Error';
	}
?>
