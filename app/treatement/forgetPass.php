<?php
	session_start();
	require_once('../require.php');

	if (isset($_POST) && isset($_POST['button']) && !empty($_POST['button']) && $_POST['button'] === "connect")
	{
		if ((isset($_POST['pass']) && !empty($_POST['pass']))
			&& (isset($_POST['valid_pass']) && !empty($_POST['valid_pass']))
		) {
			$fp_pass = ca_secu($_POST['pass']);
			$fp_validPass = ca_secu($_POST['valid_pass']);

			if ($fp_pass == $fp_validPass && isset($_SESSION['fg_user']) && !empty($_SESSION['fg_user'])) {
				$fp_pass = ca_crypt($fp_pass, null);

				$sql = 'UPDATE ca_membres SET `pass` = :pass WHERE `login` = :login';
				$array = array(':pass' => $fp_pass, ':login' => $_SESSION['fg_user']);
				if ($db->changeDb($sql, $array)) {
					$_SESSION['info'] = true;
					$_SESSION['error'] = "Votre mot de passe a été modifié.";
					header('Location: '.ADDR_HOST.'/index.php?pg=connect');
				} else {
					$_SESSION['error'] = "Une erreur est survenue. Veuillez contacter l'administrateur.";
					header('Location: '.ADDR_HOST.'/index.php?pg=connect');
				}
			} else {
				$_SESSION['error'] = "Les mots de passe sont différent.";
				header('Location: '.ADDR_HOST.'/index.php?pg=connect');
			}
		} else {
			$_SESSION['error'] = "Une erreur est survenue. Veuillez contacter l'administrateur.";
			header('Location: '.ADDR_HOST.'/index.php?pg=connect');
		}
	} else {
		header('Location: index.php');
	}
?>