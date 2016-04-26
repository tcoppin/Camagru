<?php

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

			if (strlen($sb_login) > 40) {
				$_SESSION['error'] = "Votre login est trop long.";
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

			if (!isset($_SESSION['error']) && empty($_SESSION['error'])) {
				date_default_timezone_set('France/Paris');
				$token = ca_generateToken(5).ca_generateToken(5).ca_generateToken(5).ca_generateToken(5).ca_generateToken(5);
				$sql = 'INSERT INTO `ca_membres` (`login`, `email`, `pass`, `date_inscrip`, `validate`, `token`) VALUES (:login , :email , :pass , :date_inscrip , :validate, :token)';
				$array = array(':login' => $sb_login, ':email' => $sb_email, ':pass' => $sb_pass, ':date_inscrip' => date('Y-m-d', time()), ':validate' => 0, ':token' => $token);
				if ($db->changeDb($sql, $array) == 1) {
					$subject = "Inscription à Camagru.com -- Validation";
					$valid_link = ADDR_HOST."/index.php?pg=validation&login=".$sb_login."&token=".$token;
					$content = "Bonjour ".$sb_login.",<br /><br />Merci de votre inscription sur notre site <a href=\"".ADDR_HOST."\">camagru.com</a>.<br /><br />Pour valider votre inscription merci de cliquer sur le lien suivant :<br /><a href=\"".$valid_link."\">".$valid_link."</a>";
					ca_mail($sb_email, $subject, $content);
				} else {
					echo "Fail";
				}
			} else {
				require_once('./part/error.php');
			}
		}
	}
?>
<div class="ca_container">
	<h1 class="ca_color_blue ca_no_margin">Inscription</h1>
	<p class="ca_no_margin ca_color_fer">
		Créer et gérer vos photos montages, liker et commenter ceux des autres membres. L'inscription va vous donner accès aux fonctionnalités réservées aux membres et le tout gratuitement.
	</p>
	<form action="index.php?pg=subscribe" method="post">
		<input class="js_inputSubscribe" type="text" name="login" placeholder="Pseudo" maxlength="40" required />
		<input class="js_inputSubscribe" type="email" name="email" placeholder="Email" maxlength="255" required />
		<input class="js_inputSubscribe" type="password" name="pass" placeholder="Mot de passe" required />
		<input class="js_inputSubscribe" type="password" name="valid_pass" placeholder="Confirmation" required />
		<button type="submit" name="button" value="suscribe">Inscription</button>
	</form>
</div>

<script type="text/javascript">
	var timeOutInputSubs = 0;
	function checkInputSubscribe(e) {
		clearTimeout(timeOutInputSubs);
    	timeOutInputSubs = setTimeout(function() {
			var nameInput = e.target.getAttribute('name');
			switch(nameInput) {
				case "login" :
					var myRegex = /^[a-zA-Z0-9-_]{4,39}$/; //lettres-chiffre-tiret-underscore
					break ;
				case "email" :
					var myRegex = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
					break ;
				case "pass" :
					var myRegex = /^[a-zA-Z0-9-_!]{7,18}$/ //lettres-chiffres-underscore
					break ;
				case "valid_pass" :
					var myRegex = /^[a-zA-Z0-9-_!]{7,18}$/ //lettres-chiffres-underscore
					break ;
			}
			if (myRegex && !myRegex.test(e.target.value))
				addClass(e.target, 'ca_error');
			else
				removeClass(e.target, 'ca_error');
    	}, 600);
	}

	var tabInputSubs = document.getElementsByClassName('js_inputSubscribe');
	for (var i =  0 ; i <= tabInputSubs.length - 1; i++) {
		tabInputSubs[i].addEventListener('keyup', checkInputSubscribe, false);
	}
</script>