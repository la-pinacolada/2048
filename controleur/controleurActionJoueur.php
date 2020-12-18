<?php
require_once PATH_VUE . "/Vue.php";
require_once PATH_MODELE . "/Jeu_2048DAO.php";
require_once PATH_METIER . "/Jeu_2048.php";

class controleurActionJoueur
{
    private $vue;
    private $jeuDAO;

    /**
     * controleurActionJoueur constructor.
     */
    function __construct(){
        $this->vue = new Vue();
        $this->jeuDAO = new Jeu_2048DAO();
    }

    function getAction($mesg){
        $_SESSION["action-joueur"] = $mesg;
        echo  $_SESSION["action-joueur"];
    }

    function affiche_logs(){
        $cpt = 0;
        while (isset($_SESSION["action_joueur".$cpt])){
            echo $_SESSION["action_joueur".$cpt];
            $cpt++;
        }
    }

    /**
     * Fonction principale de Jeu
     * @param string $pseudo
     * @param string $direction
     */
    function play(string $pseudo, string $direction){
    // on récupère le pseudo du joueur
    $_SESSION["pseudo"] = $pseudo;
    //on défini l'id de la partie avec le pseudo du joueur
    $id = $this->jeuDAO->getId($_SESSION["pseudo"]);
    //on récupère le meilleur score de la session du joueur
    $_SESSION["bestScore"] = $this->jeuDAO->getBestScore($pseudo);

    //création d'une partie si il y en a pas en cours
    if ($id == 0) {
        $grille = array(
            array(0, 0, 0, 0),
            array(0, 0, 0, 0),
            array(0, 0, 0, 0),
            array(0, 0, 0, 0)
        );
        //initialisation des deux premières cases
        try {
            $line_random1 = random_int(0, 3);
            $column_random1 = random_int(0, 3);
            do {
                $line_random2 = random_int(0, 3);
                $column_random2 = random_int(0, 3);
            } while ($line_random1 == $line_random2 && $column_random1 == $column_random2);
            //insertion de la valeur
            $grille[$line_random1][$column_random1] = 2;
            $grille[$line_random2][$column_random2] = 2;
        } catch (Exception $e) {
        }

        $jeu = new Jeu_2048($pseudo);
        $this->jeuDAO->insert($jeu);
        $_SESSION["grille"] = $grille;
        $_SESSION["score"] = "0";
        $_SESSION["bestScore"] = $this->jeuDAO->getBestScore($pseudo);
        //les cookies servent a préserver les données si la partie est interrompue, on les stocke 3600 secondes
        setcookie($pseudo . "grille", json_encode($_SESSION["grille"]), time() + 3600);
        setcookie($pseudo . "score", $_SESSION["score"], time() + 3600);
        setcookie($pseudo . "grille_precedente", json_encode($_SESSION["grille"]), time() + 3600);
        setcookie($pseudo . "score_precedent", $_SESSION["score"], time() + 3600);
        setcookie($pseudo . "precedent", false, time() +  3600);
        $this->vue->jeu();
    } //si une partie est en cours
    else {

        if (!isset($_GET["precedent"]) || $_GET["precedent"] != true) {
            $_SESSION["grille"] = json_decode($_COOKIE[$pseudo . "grille"], true);
            $_SESSION["score"] = $_COOKIE[$pseudo . "score"];
            // on récupère les cookies de la partie précédente
            setcookie($pseudo . "grille_precedente", json_encode($_SESSION["grille"]), time() +3600);
            setcookie($pseudo . "score_precedent", $_COOKIE[$pseudo . "score"], time() +3600);
            if ($direction == "rien") {
                $this->vue->jeu();
            }
            $bouge = 0;
            //déplacement et additionnement des cases
            switch ($direction) {
                case "up":
                    $mouvement1 = $this->deplacementHaut($_SESSION["grille"], 1);
                    $mouvement2 = $this->additionneHaut($_SESSION["grille"], 1);
                    $mouvement3 = $this->deplacementHaut($_SESSION["grille"], 1);
                    $id = $this->jeuDAO->getId($_SESSION["pseudo"]);
                    $this->jeuDAO->setScore($id, ($this->jeuDAO->getScore($id) + $mouvement2));
                    $bouge = $mouvement1 + $mouvement2 + $mouvement3;
                    break;
                case "left":
                    $mouvement1 = $this->deplacementGauche($_SESSION["grille"], 1);
                    $mouvement2 = $this->additionneGauche($_SESSION["grille"], 1);
                    $mouvement3 = $this->deplacementGauche($_SESSION["grille"], 1);
                    $id = $this->jeuDAO->getId($_SESSION["pseudo"]);
                    $this->jeuDAO->setScore($id, ($this->jeuDAO->getScore($id) + $mouvement2));
                    $bouge = $mouvement1 + $mouvement2 + $mouvement3;
                    break;
                case "down":
                    $mouvement1 = $this->deplacementBas($_SESSION["grille"], 1);
                    $mouvement2 = $this->additionnebas($_SESSION["grille"], 1);
                    $mouvement3 = $this->deplacementBas($_SESSION["grille"], 1);
                    $id = $this->jeuDAO->getId($_SESSION["pseudo"]);
                    $this->jeuDAO->setScore($id, ($this->jeuDAO->getScore($id) + $mouvement2));
                    $bouge = $mouvement1 + $mouvement2 + $mouvement3;
                    break;
                case "right":
                    $mouvement1 = $this->deplacementDroite($_SESSION["grille"], 1);
                    $mouvement2 = $this->additionneDroite($_SESSION["grille"], 1);
                    $mouvement3 = $this->deplacementDroite($_SESSION["grille"], 1);
                    $id = $this->jeuDAO->getId($_SESSION["pseudo"]);
                    $this->jeuDAO->setScore($id, ($this->jeuDAO->getScore($id) + $mouvement2));
                    $bouge = $mouvement1 + $mouvement2 + $mouvement3;
                    break;
            }

            //apparition d'une cellule à un endroit libre aléatoire si l'action a provoquée au moins un déplacement ou un additionnement
            $dispo = null;
            if ($bouge > 0) {
                $cpt = 0;
                for ($line = 0; $line < 4; $line++) {
                    for ($column = 0; $column < 4; $column++) {
                        if ($_SESSION["grille"][$line][$column] == 0) {
                            $dispo[$cpt][0] = $line;
                            $dispo[$cpt][1] = $column;
                            $cpt++;
                        }
                    }
                }
                try {
                    $cell_random = random_int(0, sizeof($dispo) - 1);
                    $value_random = random_int(1, 2);
                    $_SESSION["grille"][$dispo[$cell_random][0]][$dispo[$cell_random][1]] = $value_random * 2;
                } catch (Exception $e) {
                     }
                //vérification que la partie est terminé après avoir joué
                if (sizeof($dispo) - 1 == 0) {

                    $h1 = $this->deplacementHaut($_SESSION["grille"], 0);
                    $h2 = $this->additionneHaut($_SESSION["grille"], 0);
                    $h3 = $this->deplacementHaut($_SESSION["grille"], 0);
                    $g1 = $this->deplacementGauche($_SESSION["grille"], 0);
                    $g2 = $this->additionneGauche($_SESSION["grille"], 0);
                    $g3 = $this->deplacementGauche($_SESSION["grille"], 0);
                    $b1 = $this->deplacementBas($_SESSION["grille"], 0);
                    $b2 = $this->additionnebas($_SESSION["grille"], 0);
                    $b3 = $this->deplacementBas($_SESSION["grille"], 0);
                    $d1 = $this->deplacementDroite($_SESSION["grille"], 0);
                    $d2 = $this->additionneDroite($_SESSION["grille"], 0);
                    $d3 = $this->deplacementDroite($_SESSION["grille"], 0);
                    if ($h1 + $h2 + $h3 + $g1 + $g2 + $g3 + $b1 + $b2 + $b3 + $d1 + $d2 + $d3 == 0) {
                        setcookie($pseudo . "grille", "", time() - 3600);
                        setcookie($pseudo . "score", "", time() - 3600);
                        $grille = $_SESSION["grille"];
                        $gagne = false;
                        for ($i=0; $i<4; $i++){
                            for ($j=0; $j<4; $j++){
                                if($grille[$i][$j] >= 2048) $gagne=true;
                            }
                        }
                        $gagne == false ? $this->jeuDAO->setStatut(1, $id) : $this->jeuDAO->setStatut(2, $id);
                        $_SESSION["gagne"] = $gagne;
                        $this->vue->resultat();
                        exit(0);
                    }
                }
            }
            //on stocke les cookies pour si le joueur quitte a tous moments
            setcookie($pseudo . "grille", json_encode($_SESSION["grille"]), time() +3600);
            $score = $this->jeuDAO->getScore($id);
            $_SESSION["score"] = $score;
            $_SESSION["bestScore"] = $this->jeuDAO->getBestScore($pseudo);
            setcookie($pseudo . "score", $_SESSION["score"], time() +3600);
            $this->vue->jeu();

            }
        }
    }



    private function deplacementDroite($grille, $termine)
    {
        $bouge = 0;
        for ($i = 0; $i < 4; $i++) {
            $zero = 3;
            for ($j = 3; $j >= 0; $j--)
                if ($grille[$i][$j] != 0) {
                    $grille[$i][$zero] = $grille[$i][$j];
                    if ($zero > $j) {
                        $grille[$i][$j] = 0;
                        $bouge = 1;
                    }
                    $zero--;
                }
        }
        if ($termine == 1) {
            $_SESSION["grille"] = $grille;
        }
        return $bouge;
    }

    private function deplacementGauche($grille, $termine)
    {
        $bouge = 0;
        for ($i = 0; $i < 4; $i++) {
            $zero = 0;
            for ($j = 0; $j < 4; $j++){
                if ($grille[$i][$j] != 0) {
                    $grille[$i][$zero] = $grille[$i][$j];
                    if ($zero < $j) {
                        $grille[$i][$j] = 0;
                        $bouge = 1;
                    }
                    $zero++;
                }
            }
        }
        if ($termine == 1) {
            $_SESSION["grille"] = $grille;
        }
        return $bouge;
    }

    private function deplacementHaut($grille, $termine)
    {
        $bouge = 0;
        for ($j = 0; $j < 4; $j++) {
            $zero = 0;
            for ($i = 0; $i < 4; $i++)
                if ($grille[$i][$j] != 0) {
                    $grille[$zero][$j] = $grille[$i][$j];
                    if ($zero < $i) {
                        $grille[$i][$j] = 0;
                        $bouge = 1;
                    }
                    $zero++;
                }
        }
        if ($termine == 1) {
            $_SESSION["grille"] = $grille;
        }
        return $bouge;
    }

    private function deplacementBas($grille, $termine)
    {
        $bouge = 0;
        for ($j = 0; $j < 4; $j++) {
            $zero = 3;
            for ($i = 3; $i >= 0; $i--)
                if ($grille[$i][$j] != 0) {
                    $grille[$zero][$j] = $grille[$i][$j];
                    if ($zero > $i) {
                        $grille[$i][$j] = 0;
                        $bouge = 1;
                    }
                    $zero--;
                }
        }
        if ($termine == 1) {
            $_SESSION["grille"] = $grille;
        }
        return $bouge;
    }

    private function additionneDroite($grille, $termine)
    {
        $score = 0;
        for ($i = 0; $i < 4; $i++) {
            for ($j = 3; $j > 0; $j--) {
                if ($grille[$i][$j] == $grille[$i][$j - 1]) {
                    $grille[$i][$j] = $grille[$i][$j] + $grille[$i][$j - 1];
                    $score += $grille[$i][$j];
                    $grille[$i][$j - 1] = 0;
                }
            }
        }
        if ($termine == 1) {
            $_SESSION["grille"] = $grille;
        }
        return $score;
    }

    private function additionneGauche($grille, $termine)
    {
        $score = 0;
        for ($i = 0; $i < 4; $i++) {
            for ($j = 0; $j < 3; $j++) {
                if ($grille[$i][$j] == $grille[$i][$j + 1]) {
                    $grille[$i][$j] = $grille[$i][$j] + $grille[$i][$j + 1];
                    $score += $grille[$i][$j];
                    $grille[$i][$j + 1] = 0;
                }
            }
        }
        if ($termine == 1) {
            $_SESSION["grille"] = $grille;
        }
        return $score;
    }

    private function additionneHaut($grille, $termine)
    {
        $score = 0;
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 4; $j++) {
                if ($grille[$i][$j] == $grille[$i + 1][$j]) {
                    $grille[$i][$j] = $grille[$i][$j] + $grille[$i + 1][$j];
                    $score += $grille[$i][$j];
                    $grille[$i + 1][$j] = 0;
                }
            }
        }
        if ($termine == 1) {
            $_SESSION["grille"] = $grille;
        }
        return $score;
    }

    private function additionnebas($grille, $termine)
    {
        $score = 0;
        for ($i = 3; $i > 0; $i--) {
            for ($j = 0; $j < 4; $j++) {
                if ($grille[$i][$j] == $grille[$i - 1][$j]) {
                    $grille[$i][$j] = $grille[$i][$j] + $grille[$i - 1][$j];
                    $score += $grille[$i][$j];
                    $grille[$i - 1][$j] = 0;
                }
            }
        }
        if ($termine == 1) {
            $_SESSION["grille"] = $grille;
        }
        return $score;
    }

}