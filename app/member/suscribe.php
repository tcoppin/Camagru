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
	<h1 class="ca_color_blue ca_no_margin">Inscription</h1>
	<p class="ca_no_margin ca_color_fer">
		Créer et gérer vos photos montages, liker et commenter ceux des autres membres. L'inscription va vous donner accès aux fonctionnalités réservées aux membres et le tout gratuitement.
	</p>
	<form action="./treatement/subscribe.php" method="post">
		<div class="ca_cont_form">
			<input class="js_inputSubscribe" type="text" name="login" placeholder="Pseudo" maxlength="40" required />
			<div class="ca_err_form">Login mal formaté</div>
		</div>
		<div class="ca_cont_form">
			<input class="js_inputSubscribe" type="email" name="email" placeholder="Email" maxlength="255" required />
			<div class="ca_err_form">Email mal formaté</div>
		</div>
		<div class="ca_cont_form">
			<input class="js_inputSubscribe" type="password" name="pass" placeholder="Mot de passe" required />
			<div class="ca_err_form">Mot de passe mal formaté</div>
		</div>
		<div class="ca_cont_form">
			<input class="js_inputSubscribe" type="password" name="valid_pass" placeholder="Confirmation" required />
			<div class="ca_err_form">Confirmation mal formaté</div>
		</div>
		<button type="submit" name="button" value="suscribe">Inscription</button>
	</form>
	<p class="ca_no_margin ca_color_fer">
		Vous avez déjà un compte&nbsp;? <a href="?pg=connect">Connectez-vous</a>
	</p>
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
			if (myRegex && !myRegex.test(e.target.value)) {
				addClass(e.target, 'ca_error');
				e.target.parentElement.getElementsByClassName('ca_err_form')[0].style.display = "block";
			} else {
				removeClass(e.target, 'ca_error');
				e.target.parentElement.getElementsByClassName('ca_err_form')[0].style.display = "none";
			}
		}, 600);
	}

	var tabInputSubs = document.getElementsByClassName('js_inputSubscribe');
	for (var i =  0 ; i <= tabInputSubs.length - 1; i++) {
		tabInputSubs[i].addEventListener('keyup', checkInputSubscribe, false);
	}
</script>