--script to create the tables for the 2048
-- author: Antoine Petit

-- drop the two tables if they exist
DROP TABLE IF EXISTS Joueur;
DROP TABLE IF EXISTS Partie;


-- --------------------------------------------------------
--
-- Structure de la table `Joueur`
--

CREATE TABLE Joueur (
  pseudo TEXT NOT NULL UNIQUE,
  password TEXT,
  );

--
-- Structure de la table `Partie`
--

CREATE TABLE Partie (
  id INTEGER NOT NULL PRIMARY KEY,
  pseudo INTEGER CONSTRAINT fk_data_Joueur REFERENCES Joueur(pseudo),
  statut INTEGER NOT NULL,
  score INTEGER NOT NULL
  );



--
-- Contenu de la table `Joueur`
--

INSERT INTO `Joueur` (`pseudo`, `password`) VALUES
('titi', '$6$VsDCW/kqInRv$/bkDT4rmkNLGo704srZE1riI4u7IUUcSuuEqrdkeBJ.3RcsnEO.ihAnWvIWJ0fSoP3hVa/OpWTbhi50xQhzEk1'),
('toto', '$6$RTRffX4m9FBU$GQPzOIuRByEJMeT8r9fydj8eKfi7yurb0SQiT./3pHnG5ni2f96gboxLE4LZgCgEVMWMP6z.AxaOM8KaWJCmn0');
