<?php 

class Vue{

	function demandePseudo(){
		header("Content-type: text/html; charset=utf-8");
		?>
		<!DOCTYPE html>
		<html>
		<head> 
			<title>Connexion 2048 d'Antoine et Dimitri </title>
            <link rel="stylesheet" type="text/css" href="css/login.css"/>
		</head>
		<body>
            <h1>2048 Antoine - Dimitri</h1>
			<form action="index.php" method="post">
                <label class="lab_pseudo" for="pseudo">Entrer votre pseudo: </label>
				<input class="pseudo" type="text" name="pseudo" id="pseudo" required>
                <label class="lab_password" for="pseudo">Entrer votre password: </label>
                <input class="password" type="text" name="password" id="password" required>
				<input class="connexion" type="submit" name="connexion" value="Connexion"/>
                <input class="inscription" type="submit" name="inscription" value="S'inscire"/>
			</form>
		</body>
		</html>
        <?php
	}

	function jeu(){
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8" />
            <?php
            $TITRE = "2048 Antoine & Dimitri";
            $grille = $_SESSION["grille"];
            $SCORE = $_SESSION["score"];
            $BESTSCORE = $_SESSION["bestScore"];
            // intialisation du score
            ?>
            <title><?php echo $TITRE; ?></title>
            <link rel="stylesheet" type="text/css" href="css/jeu.css"/>
        </head>
        <body>
        <h1>2048 Antoine - Dimitri</h1>
            <div class="divJeu">
                <div class="Joueur">
                    <div class="règles">
                        <h3 class="Titre_regle">Regles de jeu :</h3>
                        <br>
                        <p>Le but du jeu est de faire glisser des tuiles sur une grille, pour combiner les tuiles de memes valeurs et creer ainsi une tuile portant le nombre 2048. </p>
                        <br>
                        <p>Le joueur peut toutefois continuer a jouer apres cet objectif atteint pour faire le meilleur score possible.</p>
                    </div>
                </div>

                <div class="Jeu">
                    <!--div Grille-->
                        <?php
                        for ($i = 0; $i < 4; $i++) {
                            echo "<div class=\"line"."\">\n";
                            for ($j = 0; $j < 4; $j++) {
                                echo "<div class=\"column"."\" style=\"background: #f3f3f3; color: black\">\n";
                                echo "<p>";
                                echo $grille[$i][$j] == 0 ? "" : $grille[$i][$j];
                                echo "</p>\n";
                                echo "</div>\n";
                            }
                            echo "</div>\n";
                        }
                        ?>

                </div>

                <div class="stats">
                    <p class="Score">Score : <?php echo $SCORE; ?></p>
                    <p class="Best">Best : <?php echo $BESTSCORE; ?></p>
                </div>
            </div>

            <div class="controleur">
                <div class="controleur_fleche">
                    <div class="up">
                        <form action="index.php" method="GET">
                            <input type="submit" name="action-joueur" value="up" />
                        </form>
                    </div>

                    <div class="left">
                        <form action="index.php" method="GET">
                            <input type="submit" name="action-joueur" value="left" />
                        </form>
                    </div>
                    <div class="down">
                        <form action="index.php" method="GET">
                            <input type="submit" name="action-joueur" value="down" />
                        </form>
                    </div>
                    <div class="right">
                        <form action="index.php" method="GET">
                            <input type="submit" name="action-joueur" value="right" />
                        </form>
                    </div>
                </div>

                <form action="index.php" method="GET">
                    <input class="leave" type="submit" name="deconnexion" value="deconnexion" />
                </form>

            </div>
            <?php
                //getAction($_GET['action-joueur']);
            ?>
        </body>
        </html>

		<?php
	}
    function resultat(){

        ?>
        <!DOCTYPE html>
        <html>
        <head>
        <?php
        $pseudo = $_SESSION["pseudo"];
        $TITRE = "2048 Antoine & Dimitri";
        $SCORE = $_SESSION["score"];
        $gagne = $_SESSION["gagne"];
        ?>
            <title><?php echo $TITRE; ?></title>
            <link rel="stylesheet" type="text/css" href="css/victory.css"/>
        </head>
        <body>
            <h1>2048 Antoine - Dimitri</h1>
            <br>
            <div class="container_resultat">
                <h1><?php echo($gagne == false ? 'Game Over' : 'Victory'); ?></h1>
                <br>
                <h2>Bravo !</h2>
                <p><?php echo($pseudo) ?></p>
                <p>votre score est de :</p>
                <p><?php echo($SCORE) ?></p>
            </div>
            <form action="index.php" method="GET">
                <input class="leave" type="submit" name="deconnexion" value="deconnexion" />
            </form>

            <form action="index.php" method="GET">
                <input class="reset" type="submit" name="action-joueur" value="Nouvelle_partie" />
            </form>
        </body>
        </html>

        <?php
    }
}

?>