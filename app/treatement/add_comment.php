<?php
	session_start();
	require_once('../require.php');

	if (isset($_POST['idPost']) && !empty($_POST['idPost']) && isset($_POST['comment']) && !empty($_POST['comment'])) {
		$_POST['idPost'] = ca_secu($_POST['idPost']);
		$_POST['comment'] = ca_secu($_POST['comment']);
		$sql = 'INSERT INTO `ca_comment` (`id_post`, `id_user`, `content`) VALUES (:idImage, :idUser, :comment)';
		$data = array('idImage' => $_POST['idPost'], 'idUser' => $_SESSION['id_user'], 'comment' => $_POST['comment']);
		if ($db->changeDb($sql, $data)) {
			$sql = 'SELECT COUNT(*) FROM `ca_comment` WHERE `id_post` = "'.$_POST['idPost'].'"';
			$rtn = $db->selectInDb($sql);
			$sql = 'SELECT * FROM `ca_membres` INNER JOIN `ca_pictures` ON `id_membre` = `id_user`';
			$info = $db->selectInDb($sql);
			$subject = "Nouveau commentaire -- Camagru.com";
			$content = "Bonjour ".$info[0]['login'].",<br /><br />".$_SESSION['user']." vient de laisser un commentaire sur votre post ".$info[0]['name_picture']."";
			ca_mail($info[0]['email'], $subject, $content);
			echo ('{"code":"900", "message":"", "nbComment":"'.$rtn[0][0].'"}');
		} else {
			echo ('{"code":"901", "message":""}');
		}
	}
?>