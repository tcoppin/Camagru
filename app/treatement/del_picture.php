<?php
	session_start();
	require_once('../require.php');

	if (isset($_POST['idPost']) && !empty($_POST['idPost'])) {
		$_POST['idPost'] = ca_secu($_POST['idPost']);
		$sql = 'DELETE FROM `ca_pictures` WHERE `id_picture` = :idPost AND `id_user` = :idUser';
		$data = array('idPost' => $_POST['idPost'], 'idUser' => $_SESSION['id_user']);
		if ($db->changeDb($sql, $data)) {
			echo ('{"code":"900", "message": "Votre photo a bien été supprimée"}');
		} else {
			echo ('{"code":"901", "message": ""}');
		}
	}
?>