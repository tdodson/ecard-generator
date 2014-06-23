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
		$mail->CharSet  = "utf-8";

		$result = $mail->Send();
		// echo $result ? 'Sent' : 'Error';
		$timestamp = time();
		$error = $mail->ErrorInfo; 

		// Logs the time when the send() function was run and whether it was successfull
		if ($result === false) {
			$file = './log.txt';
			// Open the file to get existing content
			$current = file_get_contents($file);
			// Append a new person to the file
			$current .= strftime("%m/%d/%y %I:%M:%S %P", $timestamp) . " $error\n";
			// Write the contents back to the file
			file_put_contents($file, $current);
		}
		else if ($result === true) {
			$file = './log.txt';
			$current = file_get_contents($file);
			$current .= strftime("%m/%d/%y %I:%M:%S %P", $timestamp) ."card sent\n";
			file_put_contents($file, $current);
		}
	}
?>
