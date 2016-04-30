<?php

	if (isset($_GET['login']) && !empty($_GET['login']) && isset($_GET['token']) && !empty($_GET['token'])) {
		$vl_login = ca_secu($_GET['login']);
		$vl_token = ca_secu($_GET['token']);
		$sql = "SELECT * FROM ca_membres WHERE `login` LIKE '".$vl_login."'";
		$value = $db->selectInDb($sql);
		if ($value[0]['token'] == $vl_token && $value[0]['validate'] != 1) {
			$sql = "UPDATE ca_membres SET `validate` = 1 WHERE `login` = :login";
			$array = array(':login' => $value[0]['login']);
			if ($db->changeDb($sql, $array))
				$text = "Merci, votre compte à été validé.";
		} else {
			$text = "Merci mais, votre compte est déjà validé.";
		}
	} else {
		header('Location: index.php');
	}

?>
<div class="ca_container">
	<h1 class="ca_color_blue ca_no_margin">Validation de compte</h1>
	<p class="ca_no_margin ca_color_fer">
		<?= $text; ?> Vous pouvez vous connecter sur cette <a href="./index.php?pg=connect">page</a>.
	</p>
</div>