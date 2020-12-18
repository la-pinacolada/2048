<?php


class Jeu_2048DAO{


        // attribut(s) à ajouter
        private $connexion;

    /**
    Constructeur de la classe
     */
    public function __construct(){
        $this->connexion=BDConnexion::getInstance()->getConnexion();
    }

    /**
     * créer une partie
     * @param Jeu_2048 $jeu
     */
    public function insert(Jeu_2048 $jeu){
        //on insère la nouvelle partie dans la base de donnée 
        $req = $this->connexion->prepare("INSERT INTO Partie(pseudo, statut, score) VALUES (:pseudo, :statut, :score)");
        $req->execute(array(
            //on récupère le pseudo du joueur de la partie
            "pseudo" => $jeu->getPseudo(),
            // score et statut sont à 0 de base
            "statut" => 0,
            "score" => 0
        ));
    }

    /**
     * modifie l'état d'une partie gagné ou perdue
     * @param $statut
     * @param $id
     */
    public function setStatut($statut, $id){
        $statement = $this->connexion->prepare("update Partie set statut=? where id=?;");
        $statement->bindParam(1, $statut);
        $statement->bindParam(2, $id);
        $statement->execute();
    }

    /**
     * retourne l'id de la partie en cours d'un joueur
     * @param $pseudo
     * @return int
     */
    public function getId($pseudo): int{
        $statement = $this->connexion->prepare("select id from Partie where pseudo=? and statut=0;");
        $statement->bindParam(1, $pseudo);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result != null ? $result["id"] : 0;
    }

    /**
     * retourne le score de la partie
     * @param $id
     * @return int|mixed
     */
    public function getScore($id){
        $statement = $this->connexion->prepare("select score from Partie where id=?;");
        $statement->bindParam(1, $id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result != null ? $result["score"] : 0;
    }

    /**
     * modifie le score de la partie
     * @param $id
     * @param $score
     */
    public function setScore($id, $score){
        $statement = $this->connexion->prepare("update Partie set score=? where id=?;");
        $statement->bindParam(1, $score);
        $statement->bindParam(2, $id);
        $statement->execute();
    }

    /**
     * retourne le meilleur score du joueur
     * @param $pseudo
     * @return int|mixed
     */
    public function getBestScore($pseudo){
        $req = $this->connexion->prepare("select max(score) from Partie where pseudo=?");
        $req->bindParam(1, $pseudo);
        $req->execute();
        $result = $req->fetch();
        return $result != null ? $result[0] : 0;
    }

    /**
     * retourne le statut de la partie
     * @param $id
     * @return int
     */
    public function getStatut($id): int{
        $statement = $this->connexion->prepare("select statut from Partie where id=?;");
        $statement->bindParam(1, $id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result["statut"];
    }


    }
?>