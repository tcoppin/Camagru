<?php
	session_start();
	require_once('../require.php');

	$sql = 'CREATE TABLE IF NOT EXISTS `ca_comment` (`id_post` int(11) NOT NULL, `id_user` int(11) NOT NULL, `content` text NOT NULL, `date_comment` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, KEY `id_post` (`id_post`,`id_user`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
	if (!$db->changeDb($sql, null)) {
		echo "Error when create ca_comment table";
		exit();
	}

	$sql = 'CREATE TABLE IF NOT EXISTS `ca_forgetPass` (`id _forgetPass` int(11) NOT NULL AUTO_INCREMENT, `id_user` int(11) NOT NULL, `date_mail` date NOT NULL, `token` varchar(255) NOT NULL, PRIMARY KEY (`id _forgetPass`), UNIQUE KEY `token` (`token`)) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;';
	if (!$db->changeDb($sql, null)) {
		echo "Error when create ca_forgetPass table";
		exit();
	}

	$sql = 'CREATE TABLE IF NOT EXISTS `ca_like` (`id_image` int(11) NOT NULL, `id_user` int(11) NOT NULL, `date_like` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id_image`,`id_user`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
	if (!$db->changeDb($sql, null)) {
		echo "Error when create ca_like table";
		exit();
	}

	$sql = 'CREATE TABLE IF NOT EXISTS `ca_membres` (`id_membre` int(11) NOT NULL AUTO_INCREMENT, `login` varchar(40) NOT NULL, `email` varchar(255) NOT NULL, `pass` text NOT NULL, `date_inscrip` date NOT NULL,  `validate` int(11) NOT NULL, `token` varchar(30) NOT NULL, PRIMARY KEY (`id_membre`), UNIQUE KEY `login` (`login`), UNIQUE KEY `email` (`email`)) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;';
	if (!$db->changeDb($sql, null)) {
		echo "Error when create ca_membres table";
		exit();
	}

	$sql = 'CREATE TABLE IF NOT EXISTS `ca_overPictures` (`id_overPicture` int(11) NOT NULL AUTO_INCREMENT, `file_name` varchar(255) NOT NULL, PRIMARY KEY (`id_overPicture`), UNIQUE KEY `file_name` (`file_name`)) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;';
	if (!$db->changeDb($sql, null)) {
		echo "Error when create ca_overPictures table";
		exit();
	} else {
		$sql = 'INSERT INTO `ca_overPictures` (`file_name`) VALUES (\'Dallas_Stars.png\'), (\'Montreal_Canadiens.png\'), (\'Pittsburgh_Penguins.png\'), (\'SanJose_Sharks.png\'), (\'Tampa_Bay_Lightning.png\'), (\'NHL_Shield.png\'), (\'Triforce.png\'), (\'biere.png\');';
		if (!$db->changeDb($sql, null)) {
			echo "Error when insert data in ca_overPictures table";
		}
	}

	$sql = 'CREATE TABLE IF NOT EXISTS `ca_pictures` (`id_picture` int(11) NOT NULL AUTO_INCREMENT, `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, `file_name` varchar(255) NOT NULL, `id_user` int(11) NOT NULL, `name_picture` varchar(255) NOT NULL, `delete` int(11) NOT NULL, PRIMARY KEY (`id_picture`), UNIQUE KEY `file_name` (`file_name`)) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;';
	if (!$db->changeDb($sql, null)) {
		echo "Error when create ca_pictures table";
		exit();
	}

	echo "Database create <a href=\"../index.php\">Home Page</a>";
?>