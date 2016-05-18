<?php
	session_start();
	require_once('../require.php');

	if (isset($_POST['newPage']) && !empty($_POST['newPage'])) {
		$_POST['newPage'] = ca_secu($_POST['newPage']);
		if ($_POST['newPage'] == 1)
			$sql = 'SELECT `id_picture`, `name_picture`, `file_name` FROM ca_pictures LIMIT 0, 6';
		else {
			$i = 6 * $_POST['newPage'] - 6;
			$sql = 'SELECT `id_picture`, `name_picture`, `file_name` FROM ca_pictures LIMIT '.$i.', 6';
		}
		$rtn = $db->selectInDb($sql);
		echo json_encode($rtn);
	}
?>