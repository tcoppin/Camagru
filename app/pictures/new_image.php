<?php

	session_start();
	require_once('../require.php');

	if (isset($_SESSION['user']) && !empty($_SESSION['user']) && isset($_POST['imgUser']) && !empty($_POST['imgUser']))
	{
		$_POST['name'] = ca_secu($_POST['name']);
		$fileName = date('Y-m-d_H-i-s-'.rand(), time());
		if (!is_dir("../content/tmp/"))
			mkdir("../content/tmp/");
		$path = "../content/tmp/".$fileName.".png";

		$imageData = str_replace(' ', '+', substr($_POST['imgUser'],22));
		$imageData = base64_decode($imageData);
		$source = imagecreatefromstring($imageData);
		$imageSave = imagejpeg($source, $path);
		imagedestroy($source);

		$sql = 'INSERT INTO `ca_pictures` (`file_name`, `id_user`, `delete`, `name_picture`) VALUES (:file_name , :id_user , :delete_, :name)';
		$array = array(':file_name' => $fileName, ':id_user' => $_SESSION['id_user'], ':delete_' => 0, ':name' => $_POST['name']);
		if ($db->changeDb($sql, $array)) {
			echo ('{"code": "900", "message": "Votre photo a bien été enregistrée.", "new_image": "<li><img src=\"'.ADDR_HOST.'/content/tmp/'.$fileName.'.png\" /><span class=\"ca_namePicture ca_color_blue\">'.$_POST['name'].'</span></li>"}');
		} else {
			echo ('{"code": "905", "message": "Une erreur est survenue. Veuillez recommencer ou contactez l\'administrateur."}');
		}
	}