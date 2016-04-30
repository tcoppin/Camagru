<?php
	session_start();
	require_once('require.php');

	require_once('./part/header.php');

	if (isset($_SESSION['error']) && !empty($_SESSION['error']))
		require_once('./part/error.php');

	/* Pages */
	if ($_GET['pg'] == "subscribe")
		require_once('./member/suscribe.php');
	else if ($_GET['pg'] == "validation")
		require_once('./member/validation.php');
	else if ($_GET['pg'] == "connect")
		require_once('./member/connect.php');
	else if ($_GET['pg'] == "deconnect")
		require_once('./member/deconnect.php');
	
	require_once('./part/footer.php');