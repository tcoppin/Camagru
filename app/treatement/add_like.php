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
				$sql = 'SELECT * FROM `ca_membres` INNER JOIN `ca_pictures` ON `id_membre` = `id_user`';
				$info = $db->selectInDb($sql);
				$subject = "Nouveau like -- Camagru.com";
				$content = "Bonjour ".$info[0]['login'].",<br /><br />".$_SESSION['user']." vient de liker votre post ".$info[0]['name_picture']."";
				ca_mail($info[0]['email'], $subject, $content);
				echo ('{"code":"900", "message":"", "nbLike":"'.$rtn[0][0].'"}');
			} else {
				echo ('{"code":"901", "message": "Une erreur est survenue"}');
			}
		} else {
			$sql = 'DELETE FROM `ca_like` WHERE `id_image` = :idPost AND `id_user` = :idUser';
			$data = array('idPost' => $_POST['idPost'], 'idUser' => $_SESSION['id_user']);
			if ($db->changeDb($sql, $data)) {
				$sql = 'SELECT * FROM `ca_membres` INNER JOIN `ca_pictures` ON `id_membre` = `id_user`';
				$info = $db->selectInDb($sql);
				$subject = "Nouveau like -- Camagru.com";
				$content = "Bonjour ".$info[0]['login'].",<br /><br />".$_SESSION['user']." a supprimer son like de votre post ".$info[0]['name_picture']."";
				ca_mail($info[0]['email'], $subject, $content);
				echo ('{"code":"910", "message":"", "nbLike":"'.$rtn[0][0].'"}');
			}
			else
				echo ('{"code":"901", "message":"Une erreur est survenue."}');
		}
	}
?>