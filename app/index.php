<?php
	session_start();
	require_once('require.php');

	$db = new bdd();
	$db->connect();

	require_once('./part/header.php');

	/* Pages */
	if ($_GET['pg'] == "subscribe")
		require_once('./member/suscribe.php');
	else if ($_GET['pg'] == "validation")
		require_once('./member/validation.php');
	else
		echo "<a href=\"?pg=subscribe\">Inscription</a>";
	require_once('./part/footer.php');