<?php


class Jeu_2048
{
    //Au début je voulais faire avec un Id,
    // mais j'ai vu l'option pseudo
    // TEXT NOT NULL UNIQUE et je me suis dit que
    // si il n'y avait aucun pseudo en double ou plus, il n'y avait pas besoin d'id
    //private $id;
    // pseudo du joueur
    private $pseudo;
    // statut = 1 --> gagne , statut = 0 --> perdu ou en cours
    private $statut;
    // score de la partie en cours
    //score est a 0 de base car c'est le score quand on commence une partie
    private $score;

    public function __construct($pseudo)
    {
        $this->pseudo = $pseudo;
        $this->statut = 0;
        $this->score = 0;
    }



    public function getPseudo()
    {
        return $this->pseudo;
    }
    public function getStatut()
    {
        return $this->statut;
    }

    public function getScore()
    {
        return $this->score;
    }


    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;
    }

    public function setGagne(int $gagne)
    {
        $this->gagne = $gagne;
    }

    public function setScore(int $score)
    {
        $this->score = $score;
    }
}