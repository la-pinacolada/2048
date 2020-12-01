<?php 
require_once PATH_METIER.DIRECTORY_SEPARATOR."Plateau.php";

class vue{

	function demandePseudo(){
		header("Content-type: text/html; charset=utf-8");
		?>
		<!DOCTYPE html>
		<html>
		<head> 
			<title>2048 correction: login form </title>
		</head>
		<body>
			<br>
			<br>
			<form action="index.php" method="post">
				<label for="pseudo">Entrer votre pseudo: </label>
				<input type="text" name="pseudo" id="pseudo" required>
				<br>
				<br>
				<input type="submit" name="soumettre" value="envoyer"/>
			</form>
			<br>
			<br>
		</body>
		</html>
		<?php
	}


	function Jeu($salon){
		header("Content-type: text/html; charset=utf-8");
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<title>2048 Antoine & Dimitri</title>
			<link rel="stylesheet" type="text/css" href="css/jeu.css">
		</head>
		<body>
			<h1>2048 Antoine & Dimitri :</h1>
			<div class="presentation">
				<p class="Score">Score : 0</p>
				<p class="Best">Best : 0</p>
				<button>Refresh</button>
			</div>
			
			<div class="divJeu">
				<!--div Jeu-->
			<div class="Jeu">
				<!--div Grille-->
				<div class="Grille">
					<div class="Grille-ligne">
						<div class="Grille-element">2</div>
						<div class="Grille-element">4</div>
						<div class="Grille-element">8</div>
						<div class="Grille-element">16</div>
					</div>
					<div class="Grille-ligne">
						<div class="Grille-element">32</div>
						<div class="Grille-element">64</div>
						<div class="Grille-element">128</div>
						<div class="Grille-element">256</div>
					</div>
					<div class="Grille-ligne">
						<div class="Grille-element">512</div>
						<div class="Grille-element">2048</div>
						<div class="Grille-element"></div>
						<div class="Grille-element"></div>
					</div>
					<div class="Grille-ligne">
						<div class="Grille-element"></div>
						<div class="Grille-element"></div>
						<div class="Grille-element"></div>
						<div class="Grille-element"></div>
					</div>
				</div>
			</div>
			</div>
			
		</body>
		</html>
		<?php
	}

}
?>
