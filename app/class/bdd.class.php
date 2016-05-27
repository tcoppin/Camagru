<?php

class bdd {

	private static $pdo = null;

	public static function connect() {
		try
		{
			self::$pdo = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
		}
		catch(Exception $e)
		{
			echo 'Echec de la connexion à la base de données';
			die(0);
		}
	}

	public function changeDb($sql, $data) {
		try {
			$rqt = self::$pdo->prepare($sql);
			if ($data != null && $rqt->execute($data))
				return (1);
			else if ($data == null && $rqt->execute())
				return (1);
			else
				return (0);
		} 
		catch(Exception $e)
		{
			var_dump($e);
			return (0);
		}
	}

	public function selectInDb($sql) {
		try {
			self::$pdo->beginTransaction();
			$rtn = self::$pdo->query($sql);
			$result = $rtn->fetchAll();
			self::$pdo->commit();
			return ($result);
		} 
		catch(Exception $e)
		{
			self::$pdo->rollback();
			echo 'Tout ne s\'est pas bien passé, voir les erreurs ci-dessous<br />';
			echo 'Erreur : '.$e->getMessage().'<br />';
			echo 'N° : '.$e->getCode();
			die(0);
		}
	}
}