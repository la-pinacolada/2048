<?php

require_once 'controleurAuthentification.php';



class Routeur {

  private $ctrlAuthentification;
  private $ctrlActionJoueur;
 

  public function __construct() {
    $this->ctrlAuthentification= new ControleurAuthentification();
    $this->ctrlActionJoueur= new ControleurActionJoueur();
  }

  // Traite une requête entrante
  public function routerRequete() {
      //bouton deconnexion
      if ((isset($_GET["deconnexion"]) && $_GET["deconnexion"] == true) || (isset($_GET["game-end-deconnexion"]) && $_GET["game-end-deconnexion"] == true)) {
          $this->ctrlAuthentification->deconnexion();
      }
      //bouton recommencer une partie
      else if (isset($_GET["recommencer"]) && $_GET["recommencer"] == true) {
          $this->ctrlAuthentification->recommencer();
      }
      //le joueur est connecté, il continue de jouer
      else if (isset($_SESSION["pseudo"])) {
          $this->ctrlActionJoueur->play($_SESSION["pseudo"], (isset($_GET["action-joueur"])) ? $_GET["action-joueur"] : "rien");
      }
      //connexion
      else if (isset($_POST["connexion"], $_POST["pseudo"], $_POST["password"]) && !empty($_POST["pseudo"]) && !empty($_POST["password"])) {
          $this->ctrlAuthentification->connexion($_POST["pseudo"], $_POST["password"]);
      }
      //inscription
      else if (isset($_POST["inscription"], $_POST["pseudo"], $_POST["password"]) && !empty($_POST["pseudo"]) && !empty($_POST["password"])) {
          $this->ctrlAuthentification->inscription($_POST["pseudo"], $_POST["password"]);
      }
      //sinon afficher fenêtre de connexion
      else {
          $this->ctrlAuthentification->accueil();
      }
  }
}
?>
