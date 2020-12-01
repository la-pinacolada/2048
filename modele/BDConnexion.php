<?php
require_once "SalonException.php";

class BDConnexion{


	private $connexion;
	private static $instancePDO;

// constructeur qui permet de créer la connexion au sgbd
	private function __construct(){

		$chaine="sqlite:".PATH_DATA.DIRECTORY_SEPARATOR.BD;


		try{
			$this->connexion = new PDO($chaine,LOGIN,PASSWORD);
			$this->connexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $this->connexion->exec( 'PRAGMA foreign_keys = ON;' ); //Pour le contraintes d'integrite referentielles
		}
		catch(PDOException $e){
         throw new ConnexionException("problème de connexion");

		}
	}


/** méthode qui implémente le patron singleton qui ne permet d'utiliser qu'une instance d'objet de type PDO (unze seule connexion au sgbd)
@return la seule instance d'objet PDO
*/
public static function getInstance(): BDConnexion{  
	if(is_null(self::$instancePDO)){
		self::$instancePDO = new BDConnexion();
	}
	return self::$instancePDO;
}

/** méthode qui permet de retourner une connexion
@return un objet de type PDO
*/
public function getConnexion(): PDO{
	return $this->connexion;
}


/** méthode qui permet de gérer la déconnexion au sgbd
*/
public function deconnexion(): PDO{
	$this->connexion=null;
}

}