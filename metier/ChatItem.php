<?php 
class ChatItem{
	// attributs qui correspondent aux 3 colonnes de la table salon
	private $id;
	private $idpseudo;
	private $message;
	// attribut dont on aura besoin dans la classe ChatItemDao pour récupérer des infos relatifs à une jointure
	private $pseudo;
	


	public function getId(): String{
		return $this->id;
	}

	public function getIdpseudo(): String{
		return $this->idpseudo;
	}

	public function getMessage(): String{
		return $this->message;
	}

	public function getPseudo(): String{
		return $this->pseudo;
	}

	public function setPseudo(String $pseudo){
		$this->pseudo=$pseudo;
	}

	public function setMessage(String $message){
		$this->message=$message;
	}

}
?>