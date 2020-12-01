<?php

// Classe generale de definition d'exception
class SalonException extends Exception{
 public function __construct($chaine){
 	parent::__construct($chaine);
}

}


// Exception relative à un probleme de connexion
class ConnexionException extends SalonException{
 public function __construct($chaine){
 	parent::__construct($chaine);
 }


}

// Exception relative à un probleme SQL
class SQLException extends SalonException{
	 public function __construct($chaine){
 	parent::__construct($chaine);
}
}
