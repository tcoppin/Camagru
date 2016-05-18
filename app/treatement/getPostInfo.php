<?php
	session_start();
	require_once('../require.php');

	if (isset($_POST['idPost']) && !empty($_POST['idPost'])) {
		$_POST['idPost'] = ca_secu($_POST['idPost']);
		$sql = 'SELECT COUNT(*) FROM `ca_like` WHERE `id_image` = "'.$_POST['idPost'].'"';
		$nbLike = $db->selectInDb($sql)[0][0];
		$sql = 'SELECT * FROM `ca_like` WHERE `id_image` = "'.$_POST['idPost'].'" AND `id_user` = "'.$_SESSION['id_user'].'"';
		$likeOrNot = 0;
		$rtn = $db->selectInDb($sql);
		if (!empty($rtn))
			$likeOrNot = 1;
		echo ('{"code": "900", "nbLike": "'.$nbLike.'", "likeOrNot": "'.$likeOrNot.'"}');
	}
?>