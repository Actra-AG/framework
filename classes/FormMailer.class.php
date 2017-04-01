<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 20.07.2009	CM	new version


class FormMailer
{

	public static function sendMail($to, $toName, $from, $fromName, $subject, $text, $addinfo, $txthtml, $charset = 'UTF-8')
	{

		$email_to = "\"$toName\" <$to>";

		$email_subject = $subject;
		$email_subject = str_replace("%quotes%", "\"", $email_subject);
		$email_subject = stripslashes($email_subject);

		if ($addinfo == "yes") {
			if ($txthtml == 'html') {
				$email_text = "<p>HTTP-Host: " . $_SERVER["HTTP_HOST"] . "<br />\n";
				$email_text .= "Request-URI: " . $_SERVER["REQUEST_URI"] . "<br />\n";
				$email_text .= "User-Agent: " . $_SERVER["HTTP_USER_AGENT"] . "<br />\n";
				$email_text .= "Remote-Addr: " . $_SERVER["REMOTE_ADDR"] . "<br />\n";
				$email_text .= "Remote-Port: " . $_SERVER["REMOTE_PORT"] . "</p>";
				$email_text .= $text;
			} else {
				$email_text = "HTTP-Host: " . $_SERVER["HTTP_HOST"] . "\n";
				$email_text .= "Request-URI: " . $_SERVER["REQUEST_URI"] . "\n";
				$email_text .= "User-Agent: " . $_SERVER["HTTP_USER_AGENT"] . "\n";
				$email_text .= "Remote-Addr: " . $_SERVER["REMOTE_ADDR"] . "\n";
				$email_text .= "Remote-Port: " . $_SERVER["REMOTE_PORT"] . "\n\n";
				$email_text .= $text;
			}
		} else {
			$email_text = $text;
		}

		$email_text = str_replace("\r\n", "\n", $email_text);
		$email_text = str_replace("%quotes%", "\"", $email_text);
		$email_text = stripslashes($email_text);

		$email_header = "Return-Path: <$from>\n";
		$email_header .= "Message-ID: <" . date("YmdHis") . "$to>\n";
		$email_header .= "Date: " . date("r") . "\n";
		$email_header .= "From: \"$fromName\" <$from>\n";
		$email_header .= "MIME-Version: 1.0\n";
		if ($txthtml == "html") {
			$email_header .= "Content-type: text/html; charset={$charset}\n";
		} else {
			$email_header .= "Content-type: text/plain; charset={$charset}\n";
		}
		$email_header .= "Content-Transfer-Encoding: 7bit\n";

		if (ini_get('safe_mode')) {
			$mail_send = mail($email_to, $email_subject, $email_text, $email_header);
		} else {
			$mail_send = mail($email_to, $email_subject, $email_text, $email_header, "-f{$from}");
		}

		if ($mail_send) {
			return 'erfolgreich';
		} else {
			return 'fehlgeschlagen';
		}
	}
}

/* EOF */