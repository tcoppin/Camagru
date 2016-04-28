<?php
	if (isset($_POST) && isset($_POST['button']) && !empty($_POST['button']) && $_POST['button'] === "connect")
	{
		if ((isset($_POST['login']) && !empty($_POST['login']))
			&& (isset($_POST['pass']) && !empty($_POST['pass']))
		) {
			$sb_login = ca_secu($_POST['login']);
			$sb_pass = ca_secu($_POST['pass']);
			$sb_validPass = ca_secu($_POST['valid_pass']);

			if (strlen($sb_login) > 40) {
				$_SESSION['error'] = "Votre login est trop long.";
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
	<h1 class="ca_color_blue ca_no_margin">Connexion</h1>
	<p class="ca_no_margin ca_color_fer">
		Connectez-vous pour accéder à toutes les fonctionnalités du site.
	</p>
	<form action="index.php?pg=connect" method="post">
		<input class="js_inputConnect" type="text" name="login" placeholder="Pseudo" maxlength="40" required />
		<input class="js_inputConnect" type="password" name="pass" placeholder="Mot de passe" required />
		<button type="submit" name="button" value="connect">Connexion</button>
	</form>
	<div class="ca_separation ca_margin_top_10"></div>
	<h2 class="ca_color_blue ca_no_margin ca_margin_top_10">Mot de passe oublié :</h2>
	<p class="ca_no_margin ca_color_fer">
		Vous avez oublié votre mot de passe, on vous renvoit un mail pour le changer ;)
	</p>
	<form action="index.php?pg=connect" method="post">
		<input class="js_inputConnect ca_collapse" type="email" name="email" placeholder="Email" maxlength="255" required />
		<button type="submit" name="button" value="send" class="ca_collapse">Envoyer</button>
		<div class="ca_clearb"></div>
	</form>
	<p class="ca_no+margin ca_color_fer">
		Je n'ai pas de compte et je m'en créé un <a href="?pg=subscribe">ici</a>.
	</p>
</div>

<script type="text/javascript">
	var timeOutInputConnect = 0;
	function checkInputConnect(e) {
		clearTimeout(timeOutInputConnect);
    	timeOutInputConnect = setTimeout(function() {
			var nameInput = e.target.getAttribute('name');
			switch(nameInput) {
				case "login" :
					var myRegex = /^[a-zA-Z0-9-_]{4,39}$/; //lettres-chiffre-tiret-underscore
					break ;
				case "pass" :
					var myRegex = /^[a-zA-Z0-9-_!]{7,18}$/ //lettres-chiffres-underscore
					break ;
				case "email" :
					var myRegex = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
					break ;
			}
			if (myRegex && !myRegex.test(e.target.value))
				addClass(e.target, 'ca_error');
			else
				removeClass(e.target, 'ca_error');
    	}, 600);
	}

	var tabInputSubs = document.getElementsByClassName('js_inputConnect');
	for (var i =  0 ; i <= tabInputSubs.length - 1; i++) {
		tabInputSubs[i].addEventListener('keyup', checkInputConnect, false);
	}
</script>