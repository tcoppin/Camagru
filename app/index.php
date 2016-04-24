<?php
session_start();
require_once('require.php');

$db = new bdd();
$db->connect();

require_once('./part/header.php');

/* Pages */
require_once('./member/suscribe.php');

require_once('./part/footer.php');