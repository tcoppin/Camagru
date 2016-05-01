<?php
	if ((isset($_GET['login']) && !empty($_GET['login'])) && (isset($_GET['token']) && !empty($_GET['token']))) {
		$_GET['login'] = ca_secu($_GET['login']);
		$_GET['token'] = ca_secu($_GET['token']);
		$sql = 'SELECT ca_membres.login, ca_forgetPass.token FROM `ca_forgetPass` INNER JOIN `ca_membres` ON ca_membres.id_membre = ca_forgetPass.id_user WHERE ca_forgetPass.token = "'.$_GET['token'].'"';
		$rtn = $db->selectInDb($sql);

		if ($rtn[0]['login'] !== $_GET['login'])
			echo '<meta http-equiv="Refresh" content="0; URL=index.php" />';
		else
			$_SESSION['fg_user'] = $_GET['login'];
	} else {
		echo '<meta http-equiv="Refresh" content="0; URL=index.php" />';
	}
?>

<div class="ca_container">
	<h1 class="ca_color_blue ca_no_margin">Mot de passe oublié</h1>
	<p class="ca_no_margin ca_color_fer">
		Connectez-vous pour accéder à toutes les fonctionnalités du site.
	</p>
	<form action="./treatement/forgetPass.php" method="post">
		<input class="js_inputSubscribe" type="password" name="pass" placeholder="Mot de passe" required />
		<input class="js_inputSubscribe" type="password" name="valid_pass" placeholder="Confirmation" required />
		<button type="submit" name="button" value="connect">Changer de mot de passe</button>
	</form>
</div>