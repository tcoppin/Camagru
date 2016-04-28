<!doctype html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0" />
	<link href='https://fonts.googleapis.com/css?family=Sansita+One' rel='stylesheet' type='text/css' />
	<link rel="stylesheet" type="text/css" href="assets/css/style.css" />
	<script type="text/javascript" src="assets/js/common.js"></script>
	<title>/*** Camagru ***/</title>
</head>
<body class="ca_back_perle ca_no_margin">
	<header class="ca_back_white ca_header ca_border_blue">
		<img src="assets/images/logo.png" alt="Camagru" class="ca_logo" />
		<div class="ca_nav">
			<div class="ca_burger">
				<span></span>
			</div>
			<ul class="ca_back_white">
				<li class="ca_border_blue"><a href="index.php" class="ca_color_blue">Accueil</a></li>
				<li class="ca_border_blue"><a href="?pg=subscribe" class="ca_color_blue">Inscription</a></li>
				<li class="ca_border_blue"><a href="?pg=connect" class="ca_color_blue">Connexion</a></li>
			</ul>
		</div>
	</header>
	<script type="text/javascript">
		function stateNav() {
			var list = document.getElementsByClassName('ca_nav')[0].getElementsByTagName('ul')[0];
			if (window.getComputedStyle(list,null).getPropertyValue('display') == 'none')
			{	
				list.style.display = 'block';
				addClass(document.getElementsByClassName('ca_nav')[0], 'ca_open');
			}
			else {
				list.style.display = 'none';
				removeClass(document.getElementsByClassName('ca_nav')[0], 'ca_open');
			}
		}
		document.getElementsByClassName('ca_burger')[0].addEventListener('click', stateNav, false);
	</script>
	<section>
		<article>