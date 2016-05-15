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
	else if ($_GET['pg'] == "forgetPass")
		require_once('./member/forgetpass.php');
	else if ($_GET['pg'] == "camera")
		require_once('./pictures/cam.php');
	else {
		require_once('./gallery.php');
		if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user']))
			require_once('./member/connect.php');
	}
	
	require_once('./part/footer.php');