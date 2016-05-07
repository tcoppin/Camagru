<?php

	session_start();
	require_once('../require.php');

	if (isset($_POST['imgUser']) && !empty($_POST['imgUser']))
	{
		$fileName = date('Y-m-d_H-i-s-'.rand(), time());
		if (!is_dir("../content/tmp/"))
			mkdir("../content/tmp/");
		$path = "../content/tmp/".$fileName.".png";

		$imageData = str_replace(' ', '+', substr($_POST['imgUser'],22));
		$imageData = base64_decode($imageData);
		$source = imagecreatefromstring($imageData);
		$imageSave = imagejpeg($source, $path);
		imagedestroy($source);

		echo ("<a href=\"".ADDR_HOST."/content/tmp/".$fileName.".png\" target=\"_blank\">Photo</a>");
	}