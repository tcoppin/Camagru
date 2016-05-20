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
		$sql = 'SELECT COUNT(*) FROM `ca_comment` WHERE `id_post` = "'.$_POST['idPost'].'"';
		$nbComment = $db->selectInDb($sql)[0][0];
		$sql = 'SELECT `content`, `date_comment`, `login` FROM `ca_comment` INNER JOIN `ca_membres` ON `id_user` = `id_membre` WHERE `id_post` = "'.$_POST['idPost'].'"';
		$rtn = $db->selectInDb($sql);
		$comment = array();
		foreach ($rtn as $value) {
			$comment[] = '{"login": "'.$value['login'].'", "content": "'.$value['content'].'", "date_comment": "'.date('d-m-Y H:i:s', strtotime($value['date_comment'])).'"}';
		 }
		// echo ('{"code": "900", "nbLike": "'.$nbLike.'", "likeOrNot": "'.$likeOrNot.'", "nbComment": "'.$nbComment.'"}');
		$json = array();
		$json['code'] = "900";
		$json['nbLike'] = $nbLike;
		$json['likeOrNot'] = $likeOrNot;
		$json['nbComment'] = $nbComment;
		$json['comment'] = $comment;
		echo json_encode($json);
		// echo ('{"code": "900", "nbLike": "'.$nbLike.'", "likeOrNot": "'.$likeOrNot.'", "nbComment": "'.$nbComment.'", "comment": '.$comment.'}');
	}
?>