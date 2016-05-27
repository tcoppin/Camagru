<?php

	function ca_secu($toSecu) {
		if (is_numeric($toSecu))
			return ($toSecu);
		$toSecu = trim($toSecu);
		$toSecu = stripslashes($toSecu);
		$toSecu = htmlspecialchars($toSecu);
		return ($toSecu);
	}

	function ca_generateToken($size) {
		$data = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ@';
		$data = str_shuffle($data);
		return (substr($data, 0, $size));
	}

	function ca_crypt($toCrypt, $salt) {
		if (!isset($salt) || $salt == null || empty($salt))
			$salt = ca_generateToken(3);
		return ($salt.":".sha1(crypt(md5($toCrypt), $salt."catc")));
	}

	function ca_mail($toMail, $subject, $content) {
		$nl = "\r\n";
		$boundary = "-----=".md5(rand());
		$header = "From: \"No-Reply Camagru\"<no-reply@42.fr>".$nl;
		$header .= "MIME-Version: 1.0".$nl;
		$header .= "Content-Type: multipart/alternative;".$nl." boundary=\"$boundary\"".$nl;

		$message.= $passage_ligne."--".$boundary.$nl;
		$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$nl;
		$message.= "Content-Transfer-Encoding: 8bit".$nl;
		$message.= $nl.$content.$nl;
		$message.= $nl."--".$boundary."--".$nl;
		$message.= $nl."--".$boundary."--".$nl;

		mail($toMail, $subject, $message, $header);
	}