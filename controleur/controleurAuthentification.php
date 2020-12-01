<?php
require_once PATH_VUE.DIRECTORY_SEPARATOR."vue.php";
require_once PATH_MODELE.DIRECTORY_SEPARATOR."UserDao.php";
require_once PATH_MODELE.DIRECTORY_SEPARATOR."ChatItemDao.php";
require_once PATH_METIER.DIRECTORY_SEPARATOR."ChatItem.php";

class ControleurAuthentification{

private $vue;
private $userDao;
private $chatItemDao;

function __construct(){
$this->vue=new Vue();
$this->userDao=new UserDao();
$this->chatItemDao=new ChatItemDao();
}

function accueil(){
$this->vue->demandePseudo();
}

function verifierAuthentification($pseudo){
if ($this->userDao->exists($pseudo)){
$salon=$this->chatItemDao->findMessages(10);
$this->vue->demandeMessage($salon);
}
else{
$this->vue->demandePseudo();
}

}

}
