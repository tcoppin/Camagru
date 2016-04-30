<?php

	require_once(dirname(__FILE__)."/config/database.php");
	require_once(dirname(__FILE__)."/config/define.php");

	// Class
	require_once(dirname(__FILE__)."/class/bdd.class.php");

	require_once(dirname(__FILE__)."/functions.php");

	$db = new bdd();
	$db->connect();