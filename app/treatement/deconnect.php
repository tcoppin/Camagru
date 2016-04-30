<?php
	session_start();

	unset($_SESSION['user']);
	unset($_SESSION['token']);
	header('Location: ../index.php');

?>