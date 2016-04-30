<?php

	session_start();
	require_once('../require.php');

	if (isset($_POST) && isset($_POST['button']) && !empty($_POST['button']) && $_POST['button'] === "connect")
	{
		if ((isset($_POST['login']) && !empty($_POST['login']))
			&& (isset($_POST['pass']) && !empty($_POST['pass']))
		) {
			$co_login = ca_secu($_POST['login']);
			$co_pass = ca_secu($_POST['pass']);

			$sql = 'SELECT `login`, `email`, `pass`, `date_inscrip`, `validate` FROM `ca_membres` WHERE `login` = "'.$co_login.'"';
			$value = $db->selectInDb($sql);
			
			if (!isset($value[0]) || empty($value[0])) {
				$_SESSION['error'] = 'Login inconnu.';
				header('Location: ../index.php?pg=connect');
			}
			$salt = substr($value[0]['pass'], 0, 3);
			$co_pass = ca_crypt($co_pass, $salt);

			if ($value[0]['validate'] == "0") {
				$_SESSION['error'] = 'Le compte '.$co_login.' n\'est pas validé, veuillez vérifier votre boite mail.';
				header('Location: ../index.php?pg=connect');
			} else if ($co_pass == $value[0]['pass']) {
				$token = ca_generateToken(8).ca_generateToken(8).ca_generateToken(8).ca_generateToken(8).ca_generateToken(8);
				$_SESSION['user'] = $value[0]['login'];
				$_SESSION['token'] = $token;
				$_SESSION['error'] = 'Connexion réussie.';
				$_SESSION['info'] = true;
				header('Location: ../index.php');
			} else {
				$_SESSION['error'] = 'Mot de passe incorrect.';
				header('Location: ../index.php?pg=connect');
			}
		} else {
			$_SESSION['error'] = "Une erreur est survenue. Veuillez recommencer ou contactez l'administrateur.";
			header('Location: '.ADDR_HOST.'/index.php?pg=subscribe');
		}
	} else if (isset($_POST) && isset($_POST['button']) && !empty($_POST['button']) && $_POST['button'] === "sendPassForget") {
		if (isset($_POST['email']) && !empty($_POST['email'])) {
			
		} else {
			$_SESSION['error'] = "Une erreur est survenue. Veuillez recommencer ou contactez l'administrateur.";
			header('Location: '.ADDR_HOST.'/index.php?pg=subscribe');
		}
	} else {
		header('Location: index.php');
	}

?>