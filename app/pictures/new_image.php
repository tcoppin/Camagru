<?php

	session_start();
	require_once('../require.php');

	if (isset($_SESSION['user']) && !empty($_SESSION['user']) && isset($_POST['imgUser']) && !empty($_POST['imgUser']))
	{

		$_POST['name'] = ca_secu($_POST['name']);
		// $_POST['widthOP'] = ca_secu($_POST['widthOP']);
		// $_POST['heightOP'] = ca_secu($_POST['heightOP']);
		$_POST['name'] = ca_secu($_POST['name']);
		$_POST['name'] = ca_secu($_POST['name']);
		$fileName = date('Y-m-d_H-i-s-'.rand(), time());
		if (!is_dir("../content/tmp/"))
			mkdir("../content/tmp/");
		$path = "../content/tmp/".$fileName.".png";

		$sql = 'SELECT * FROM ca_overPictures WHERE `id_overPicture` = "'.$_POST['idOP'].'"';
		$value = $db->selectInDb($sql);

		$overPic = imagecreatefrompng('../content/overPicture/'.$value[0]['file_name']);

		$imageData = str_replace(' ', '+', substr($_POST['imgUser'],22));
		$imageData = base64_decode($imageData);
		$source = imagecreatefromstring($imageData);
		$overPic2 = imagecreatetruecolor($_POST['widthOP'], $_POST['heightOP']);
		imagecolortransparent($overPic2, imagecolorallocatealpha($overPic2, 0, 0, 0, 127));
		imagealphablending($overPic2, false);
		imagesavealpha($overPic2, true);
		imagecopyresampled($overPic2, $overPic, 0, 0, 0, 0, $_POST['widthOP'], $_POST['heightOP'], imagesx($overPic), imagesy($overPic));
		imagecopyresampled($source, $overPic2, 0, 0, 0, 0, $_POST['widthOP'], $_POST['heightOP'], $_POST['widthOP'], $_POST['heightOP']);
		$imageSave = imagepng($source, $path);
		imagedestroy($source);
		imagedestroy($overPic);
		imagedestroy($overPic2);

		$sql = 'INSERT INTO `ca_pictures` (`file_name`, `id_user`, `delete`, `name_picture`) VALUES (:file_name , :id_user , :delete_, :name)';
		$array = array(':file_name' => $fileName, ':id_user' => $_SESSION['id_user'], ':delete_' => 0, ':name' => $_POST['name']);
		if ($db->changeDb($sql, $array)) {
			echo ('{"code": "900", "message": "Votre photo a bien été enregistrée.", "new_image": "<li><span class=\"ca_del_pic\" onclick=\"delPicture(event);\"></span><img src=\"'.ADDR_HOST.'/content/tmp/'.$fileName.'.png\" /><span class=\"ca_namePicture ca_color_blue\">'.$_POST['name'].'</span></li>"}');
		} else {
			echo ('{"code": "905", "message": "Une erreur est survenue. Veuillez recommencer ou contactez l\'administrateur."}');
		}
	}