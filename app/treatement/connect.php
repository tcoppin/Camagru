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

			$sql = 'SELECT `id_membre`, `login`, `email`, `pass`, `date_inscrip`, `validate` FROM `ca_membres` WHERE `login` = "'.$co_login.'"';
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
				$_SESSION['id_user'] = $value[0]['id_membre'];
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
			header('Location: '.ADDR_HOST.'/index.php?pg=subscribe');
		}
	} else if (isset($_POST) && isset($_POST['button']) && !empty($_POST['button']) && $_POST['button'] === "sendPassForget") {
		if (isset($_POST['email']) && !empty($_POST['email'])) {
			$fp_mail = ca_secu($_POST['email']);

			if (!filter_var($fp_mail, FILTER_VALIDATE_EMAIL)) {
				$_SESSION['error'] = "Adresse email incorrecte.";
				header('Location: '.ADDR_HOST.'/index.php?pg=connect');
			}

			$token = md5(crypt(ca_generateToken(8).ca_generateToken(8).ca_generateToken(8).ca_generateToken(8), ca_generateToken(8)));

			$sql = 'SELECT `id_membre`, `login` FROM `ca_membres` WHERE `email` = "'.$fp_mail.'"';
			$rtn = $db->selectInDb($sql);

			if ($rtn) {
				$sql = 'INSERT INTO `ca_forgetPass` (`id_user`, `date_mail`, `token`) VALUES (:idUser, :dateMail, :token)';
				$array = array(':idUser' => $rtn[0]['id_membre'], ':dateMail' => date('Y-m-d', time()), ':token' => $token);
				$rsl = $db->changeDb($sql, $array);
				if ($rsl) {
					$subject = "Camagru.com -- Mot de passe oublié";
					$forgetPass_link = ADDR_HOST."/index.php?pg=forgetPass&login=".$rtn[0]['login']."&token=".$token;
					$content = "Bonjour ".$rtn[0]['login'].",<br /><br />Ce mail est un mail de changement de mot de passe <a href=\"".ADDR_HOST."\">camagru.com</a>.<br /><br />Pour changer votre votre mot de passe merci de cliquer sur le lien suivant :<br /><a href=\"".$forgetPass_link."\">".$forgetPass_link."</a>";
					ca_mail($fp_mail, $subject, $content);
					$_SESSION['error'] = "Un e-mail a été envoyer pour changer votre mot de passe.";
					$_SESSION['info'] = true;
					header('Location: '.ADDR_HOST.'/index.php?pg=connect');
				} else {
					$_SESSION['error'] = "Une erreur est survenue. Veuillez recommencer ou contactez l'administrateur. 4165";
					header('Location: '.ADDR_HOST.'/index.php?pg=connect');
				}
			} else {
				$_SESSION['error'] = "Adresse email inconnue.";
				header('Location: '.ADDR_HOST.'/index.php?pg=connect');
			}
		} else {
			$_SESSION['error'] = "Une erreur est survenue. Veuillez recommencer ou contactez l'administrateur.";
			header('Location: '.ADDR_HOST.'/index.php?pg=connect');
		}
	} else {
		header('Location: index.php');
	}

?>