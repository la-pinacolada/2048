<?php
require_once PATH_METIER . DIRECTORY_SEPARATOR . "User.php";
require_once "BDConnexion.php";
require_once "BDException.php";

class UserDao{

	// ajouter le(s) attribut(s)
	private $connexion;


    /**
     * UserDao constructor.
     */
	public function __construct(){
		$this->connexion=BDConnexion::getInstance()->getConnexion();
	}


    /**
    méthode qui détermine si un Joueur avec un certain pseudo est dans la table pseudo
    @param $pseudo un pseudo
    @return true si le pseudo passé en pramètre correspond à un utilisateur dans la table utilisateur, false sinon
    @throws TableAccesException si la requête SQL pose problème
     */
    public function exists(string $pseudo): bool{
        try {
            $statement = $this->connexion->prepare("select pseudo from Joueur where pseudo=?;");
            $statement->bindParam(1, $pseudo);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            if ($result["pseudo"]!=Null){
                return true;
            }
            else{
                return false;
            }
        } catch (PDOException $e) {
            throw new SQLException($e);
        }
    }

    /**
     * Vérifie que le password correspond au pseudo
     *
     * @param string $pseudo
     * @param string $pwd
     * @return bool
     * @throws SQLException
     */
    public function verifierMdp(string $pseudo, string $pwd): bool{
        try {
            $statement = $this->connexion->prepare("select password from Joueur where pseudo=?;");
            $statement->bindParam(1, $pseudo);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return password_verify($pwd, $result["password"]);
        } catch (PDOException $e) {
            throw new SQLException("problème requête SQL sur la table Joueur");

        }
    }

    /**
     * créer un joueur
     *
     * @param string $pseudo
     * @param string $pwd
     */
    public function add(string $pseudo, string $pwd){
        $req = $this->connexion->prepare("INSERT INTO Joueur(pseudo, password) VALUES (:pseudo, :password)");
        $req->execute(array(
            "pseudo" => $pseudo,
            "password" => $pwd
        ));
    }
}
