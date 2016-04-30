<?php
	session_start();
	require_once('../require.php');

	if (isset($_POST) && isset($_POST['button']) && !empty($_POST['button']) && $_POST['button'] === "suscribe")
	{
		if ((isset($_POST['login']) && !empty($_POST['login']))
			&& (isset($_POST['pass']) && !empty($_POST['pass']))
			&& (isset($_POST['valid_pass']) && !empty($_POST['valid_pass']))
			&& (isset($_POST['email']) && !empty($_POST['email']))
		) {
			$sb_login = ca_secu($_POST['login']);
			$sb_email = ca_secu($_POST['email']);
			$sb_pass = ca_secu($_POST['pass']);
			$sb_validPass = ca_secu($_POST['valid_pass']);

			/* Traitement du login */
			if (strlen($sb_login) > 40) {
				$_SESSION['error'] = "Votre login est trop long.";
			} else if (!preg_match("/^[a-zA-Z0-9-_]{4,39}$/", $sb_login)) {
				$_SESSION['error'] = "Votre login ne peut contenir que des lettres (majuscules ou minuscules), des chiffres, un \"-\" ou un \"_\"";
			}


			if (strlen($sb_email) > 255) {
				$_SESSION['error'] = "Votre email est trop long.";
			} else if (!filter_var($sb_email, FILTER_VALIDATE_EMAIL)) {
				$_SESSION['error'] = "Adresse email incorrecte.";
			}
			
			if ($sb_pass !== $sb_validPass) {
				$_SESSION['error'] = "Les mots de passe différent.";
			} else {
				$sb_pass = ca_crypt($sb_pass, null);
			}

			$sql = "SELECT `login`, `email` FROM `ca_membres` WHERE `login` = '".$sb_login."' OR `email` = '".$sb_email."'";
			$rtn = $db->selectInDb($sql);

			if ($rtn[0]['login'] == $sb_login) {
				$_SESSION['error'] = "Le login est déjà utilisé par un autre utilisateur.";
			} else if ($rtn[0]['email'] == $sb_email) {
				$_SESSION['error'] = "Un compte est déjà lié à cette adresse email.";
			}

			if (!isset($_SESSION['error']) && empty($_SESSION['error'])) {
				date_default_timezone_set('France/Paris');
				$token = ca_generateToken(5).ca_generateToken(5).ca_generateToken(5).ca_generateToken(5).ca_generateToken(5);
				$sql = 'INSERT INTO `ca_membres` (`login`, `email`, `pass`, `date_inscrip`, `validate`, `token`) VALUES (:login , :email , :pass , :date_inscrip , :validate, :token)';
				$array = array(':login' => $sb_login, ':email' => $sb_email, ':pass' => $sb_pass, ':date_inscrip' => date('Y-m-d', time()), ':validate' => 0, ':token' => $token);
				if ($db->changeDb($sql, $array)) {
					$subject = "Inscription à Camagru.com -- Validation";
					$valid_link = ADDR_HOST."/index.php?pg=validation&login=".$sb_login."&token=".$token;
					$content = "Bonjour ".$sb_login.",<br /><br />Merci de votre inscription sur notre site <a href=\"".ADDR_HOST."\">camagru.com</a>.<br /><br />Pour valider votre inscription merci de cliquer sur le lien suivant :<br /><a href=\"".$valid_link."\">".$valid_link."</a>";
					ca_mail($sb_email, $subject, $content);
					$_SESSION['error'] = "Inscription réussie. Un e-mail a été envoyer pour valider votre compte.";
					$_SESSION['info'] = true;
					header('Location: '.ADDR_HOST.'/index.php');
				} else {
					$_SESSION['error'] = "Une erreur est survenue. Veuillez recommencer ou contactez l'administrateur.";
					header('Location: '.ADDR_HOST.'/index.php?pg=subscribe');
				}
			} else {
				$_SESSION['error'] = "Une erreur est survenue. Veuillez recommencer ou contactez l'administrateur.";
				header('Location: '.ADDR_HOST.'/index.php?pg=subscribe');
			}
		}
	} else {
		header('Location: ../index.php');
	}