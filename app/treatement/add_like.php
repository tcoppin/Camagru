<?php
	session_start();
	require_once('../require.php');

	if (isset($_POST['idPost']) && !empty($_POST['idPost'])) {
		$_POST['idPost'] = ca_secu($_POST['idPost']);
		$sql = 'SELECT * FROM `ca_like` WHERE `id_image` = "'.$_POST['idPost'].'" AND `id_user` = "'.$_SESSION['id_user'].'"';
		$rtn = $db->selectInDb($sql);
		if (empty($rtn)) {
			$sql = 'INSERT INTO ca_like (`id_image`, `id_user`) VALUES (:idImage, :idUser)';
			$data = array('idImage' => $_POST['idPost'], 'idUser' => $_SESSION['id_user']);
			if ($db->changeDb($sql, $data)) {
				$sql = 'SELECT COUNT(*) FROM `ca_like` WHERE `id_image` = "'.$_POST['idPost'].'"';
				$rtn = $db->selectInDb($sql);
				echo ('{"code":"900", "message":"", "nbLike":"'.$rtn[0][0].'"}');
			} else {
				echo ('{"code":"901", "message":""}');
			}
		} else {
			$sql = 'DELETE FROM `ca_like` WHERE `id_image` = :idPost AND `id_user` = :idUser';
			$data = array('idPost' => $_POST['idPost'], 'idUser' => $_SESSION['id_user']);
			if ($db->changeDb($sql, $data))
				echo ('{"code":"910", "message":"", "nbLike":"'.$rtn[0][0].'"}');
			else
				echo ('{"code":"901", "message":""}');
		}
	}
?>