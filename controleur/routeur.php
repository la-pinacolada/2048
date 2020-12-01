<?php

require_once 'controleurAuthentification.php';



class Routeur {

  private $ctrlAuthentification;
  private $ctrlMessage;
 

  public function __construct() {
    $this->ctrlAuthentification= new ControleurAuthentification();
    $this->ctrlMessage= new ControleurMessage();
  }

  // Traite une requête entrante
  public function routerRequete() {


try{

if (!empty($_POST['pseudo'])){
setcookie("pseudo",$_POST['pseudo']);
$this->ctrlAuthentification->verifierAuthentification($_POST['pseudo']);
} 
else{
	if (!empty($_POST['message'])){
             $this->ctrlMessage->ajoutMessage($_POST['message']);

		}
	

	else{
	$this->ctrlAuthentification->accueil();
	}
}

 
     
}
    catch (SQLException $e) {
      echo $e->getMessage();
    }
    catch (ConnexionException $e) {
      echo $e->getMessage();
    }
    
    
   
 }

}
  



?>
