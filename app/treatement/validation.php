<?php

	session_start();
	require_once('../require.php');

	if (isset($_GET['login']) && !empty($_GET['login']) && isset($_GET['token']) && !empty($_GET['token'])) {
		$vl_login = ca_secu($_GET['login']);
		$vl_token = ca_secu($_GET['token']);
		$sql = "SELECT * FROM ca_membres WHERE `login` LIKE '".$vl_login."'";
		$value = $db->selectInDb($sql);
		if ($value[0]['token'] == $vl_token && $value[0]['validate'] != 1) {
			$sql = "UPDATE ca_membres SET `validate` = 1 WHERE `login` = :login";
			$array = array(':login' => $value[0]['login']);
			if ($db->changeDb($sql, $array)) {
				$_SESSION['error'] = "Merci, votre compte a été validé.";
				$_SESSION['info'] = true;
				header('Location: index.php?pg=connect');
			} else {
				$_SESSION['error'] = "Une erreur est survenue. Veuillez recommencer ou contactez l'administrateur.";
				header('Location: index.php');
			}
		} else {
			$_SESSION['error'] = "Merci mais, votre compte est déjà validé.";
			$_SESSION['info'] = true;
			header('Location: index.php?pg=connect');
		}
	} else {
		header('Location: index.php');
	}

?>