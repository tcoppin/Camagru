<style type="text/css" media="screen">
	.ca_cont_form {
		position: relative;
	}
	.ca_err_form {
		display: none;
		position: absolute;
		bottom: 43px;
		right: 10px;
		background-color: #BE2F37;
		padding: 5px 10px;
		color: #FFFFFF;
		border-radius: 8px;
	}
</style>

<div class="ca_container">
	<h1 class="ca_color_blue ca_no_margin">Connexion</h1>
	<p class="ca_no_margin ca_color_fer">
		Connectez-vous pour accéder à toutes les fonctionnalités du site.
	</p>
	<form action="./treatement/connect.php" method="post">
		<div class="ca_cont_form">
			<input class="js_inputConnect" type="text" name="login" placeholder="Pseudo" maxlength="40" required />
			<div class="ca_err_form">Login mal formaté</div>
		</div>
		<div class="ca_cont_form">
			<input class="js_inputConnect" type="password" name="pass" placeholder="Mot de passe" required />
			<div class="ca_err_form">Mot de passe mal formaté</div>
		</div>
		<button type="submit" name="button" value="connect">Connexion</button>
	</form>
	<div class="ca_separation ca_margin_top_10"></div>
	<h2 class="ca_color_blue ca_no_margin ca_margin_top_10">Mot de passe oublié :</h2>
	<p class="ca_no_margin ca_color_fer">
		Vous avez oublié votre mot de passe, on vous renvoit un mail pour le changer ;)
	</p>
	<form action="./treatement/connect.php" method="post">
		<input class="js_inputConnect ca_collapse" type="email" name="email" placeholder="Email" maxlength="255" required />
		<button type="submit" name="button" value="sendPassForget" class="ca_collapse">Envoyer</button>
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
			if (myRegex && !myRegex.test(e.target.value)) {
				addClass(e.target, 'ca_error');
				e.target.parentElement.getElementsByClassName('ca_err_form')[0].style.display = "block";
			} else {
				removeClass(e.target, 'ca_error');
				e.target.parentElement.getElementsByClassName('ca_err_form')[0].style.display = "none";
			}
    	}, 600);
	}

	var tabInputSubs = document.getElementsByClassName('js_inputConnect');
	for (var i =  0 ; i <= tabInputSubs.length - 1; i++) {
		tabInputSubs[i].addEventListener('keyup', checkInputConnect, false);
	}
</script>