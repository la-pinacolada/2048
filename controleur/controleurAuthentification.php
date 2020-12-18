<?php
require_once PATH_VUE.DIRECTORY_SEPARATOR."vue.php";
require_once PATH_MODELE . DIRECTORY_SEPARATOR . "UserDao.php";
require_once PATH_MODELE.DIRECTORY_SEPARATOR."Jeu_2048Dao.php";
require_once PATH_METIER.DIRECTORY_SEPARATOR."Jeu_2048.php";
require_once PATH_CONTROLEUR.DIRECTORY_SEPARATOR."controleurActionJoueur.php";

class ControleurAuthentification{

    private $vue;
    private $userDao;


    function __construct(){
    $this->vue=new Vue();
    $this->userDao=new UserDao();
    }

    function accueil(){
    $this->vue->demandePseudo();
    }

    function connexion(string $pseudo, string $pwd){
        $userDAO = new UserDao();
        //le joueur est authentifié
        if ($userDAO->exists($pseudo) && $userDAO->verifierMdp($pseudo, $pwd)) {
            //le joueur a entré le bon mot de passe
            $ctrlJeu = new controleurActionJoueur();
            // il commence une partie
            $ctrlJeu->play($pseudo, "rien");
        }
        //le joueur est  non authentifié, on, lui redemande son pseudo
        else $this->vue->demandePseudo();
    }

    function inscription(string $pseudo, string $pwd){
        $userDAO = new UserDao();
        //le joueur peut s'inscrire car so pseudo n'existe pas
        if (!$userDAO->exists($pseudo)) {
            // on attricu le password entré au pseudo du joueur
            $userDAO->add($pseudo, password_hash($pwd, PASSWORD_DEFAULT));
            $ctrlJeu = new controleurActionJoueur();
            // il commence une partie
            $ctrlJeu->play($pseudo, "rien");
        }
        //le joueur existe déjà, on lui redemande un nouveau pseudo non existant
        else $this->vue->demandePseudo();
    }

    function deconnexion(){
        //fin de la session
        session_destroy();
        // on redirige vers index.php
        header("Location: index.php");
    }

    function recommencer(){
        $Jeu_2048DAO = new Jeu_2048DAO();
        $pseudo = $_SESSION["pseudo"];
        $id = $Jeu_2048DAO->getId($pseudo);

        setcookie($pseudo."grille", "", time() - 3600);
        setcookie($pseudo."score", "", time() - 3600);;
        $grille = $_SESSION["grille"];
        //
        $gagne = false;
        for ($i=0; $i<4; $i++){
            for ($j=0; $j<4; $j++){
                if($grille[$i][$j] >= 2048) $gagne=true;
            }
        }
        $gagne == false ? $Jeu_2048DAO->setStatut(1, $id) : $Jeu_2048DAO->setStatut(2, $id);
        // on redirige vers index.php
        header("Location: index.php");
    }
}
