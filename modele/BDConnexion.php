<?php
require_once("BDException.php");

class BDConnexion{
	

	private $connexion;
	private static $instancePDO;

	private function  __construct() {
		try {
			//à la place utiliser une constante qui sera initialisée dans config/config.php
			$dir = dirname(__DIR__);
			//echo $dir;
			$this->connexion = new PDO("sqlite:$dir/db2048");
			$this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo $e->getMessage();
			throw new ConnexionException("problème de connexion");
		}
	}

	public static function getInstance(): BDConnexion{
		if(is_null(self::$instancePDO)){
			self::$instancePDO = new BDConnexion();
		}
		return self::$instancePDO;
	}


	public function getConnexion(): PDO{
		return $this->connexion;
	}

	public function closeConnexion() {
		$this->connexion=null;
	}

}
?>
