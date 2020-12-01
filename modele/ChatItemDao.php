<?php
require_once PATH_METIER.DIRECTORY_SEPARATOR."ChatItem.php"; 
require_once "SalonException.php";
require_once "BDConnexion.php";

class ChatItemDao{

	// attribut(s) à ajouter
	private $connexion;

/**
Constructeur de la classe
*/
public function __construct(){
	$this->connexion=BDConnexion::getInstance()->getConnexion();
}


/**
insertion d'un ChatItem dans la table salon
@param $chatItem à insérer
@return vrai si l'ajout a été effectué false sinon
@throws SQLException si problème lié à la requête SQL
*/
public function insert(ChatItem $chatItem): bool{
	try{ 


		$statement = $this->connexion->prepare("select id from utilisateurs where pseudo=?;");
		$statement->bindParam(1,$pseudoParam);
		$pseudoParam=$chatItem->getPseudo();
		$statement->execute();

		$result=$statement->fetch(PDO::FETCH_ASSOC); 
		if ($result!=null){
			$statement = $this->connexion->prepare("INSERT INTO salon (idpseudo, message) VALUES (?,?);");
			$message=$chatItem->getMessage();
			$statement->bindParam(1, $result['id']);
			$statement->bindParam(2, $message);
			$statement->execute();
			return true;
		}
		else{
			return false;
		}
	}
	catch(PDOException $e){
		throw new SQLException("problème SQL");
	}   
}



/**
permet de récupérer les x derniers messages de la conversation sur le salon
@param $number le nombre de derniers messages à récupérer
@return un tableau de ChatItem dont le pseudo et le message sont renseignés
@throws SQLException si problème avec la requête SQL

*/

public function findMessages(int $number ): array{
	try{  $requete="SELECT utilisateurs.pseudo ,salon.message FROM salon, utilisateurs where salon.idpseudo=utilisateurs.id ORDER BY salon.id DESC LIMIT 0, $number;";
	$statement=$this->connexion->query($requete);
	return $statement->fetchAll(PDO::FETCH_CLASS, "ChatItem");

	} 
	catch(PDOException $e){
	throw new SQLException("problème SQL");
	}
}


}
?>