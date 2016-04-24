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

	public function insertInto($sql, $data) {
		try {
			// var_dump($sql);
			// var_dump($data);
			$rqt = self::$pdo->prepare($sql);
			$rqt->execute($data);
		} 
		catch(Exception $e)
		{
			var_dump($e);
			die(0);
		}
	}

	public function selectInDb() {
		try {
			self::$pdo->beginTransaction();
			$rtn = self::$pdo->query("SELECT * FROM ca_membres");
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