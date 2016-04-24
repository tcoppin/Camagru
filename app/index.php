<?php
require_once('require.php');

$db = new bdd();
$db->connect();
print_r($db->selectInDb());