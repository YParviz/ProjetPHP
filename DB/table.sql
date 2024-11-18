DROP TABLE Valider;
DROP TABLE Cacher;
DROP TABLE Sanctionner;
DROP TABLE Contenir;
DROP TABLE Signaler;
DROP TABLE Voter;
DROP TABLE Argument;
DROP TABLE Camp;
DROP TABLE Debat;
DROP TABLE Categorie;
DROP TABLE Utilisateur;
DROP TYPE roleUtilisateur;

CREATE TYPE roleUtilisateur AS ENUM('Utilisateur', 'Moderateur', 'Administrateur');

CREATE TABLE Utilisateur(
   id_utilisateur SERIAL,
   email VARCHAR(50),
   pseudo VARCHAR(50),
   mdp VARCHAR(50),
   role roleUtilisateur DEFAULT 'Utilisateur',
   date_creation DATE,
   PRIMARY KEY(id_utilisateur)
);

CREATE TABLE Categorie(
   nom_c VARCHAR(50),
   desc_c VARCHAR(50),
   PRIMARY KEY(nom_c)
);

CREATE TABLE Debat(
   id_debat SERIAL,
   est_valide BOOLEAN,
   nom_d VARCHAR(50),
   desc_d VARCHAR(50),
   duree INTERVAL,
   date_creation DATE,
   id_utilisateur INT NOT NULL,
   PRIMARY KEY(id_debat),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur)
);

CREATE TABLE Camp(
   id_camp SERIAL,
   nom_camp VARCHAR(50),
   id_debat INT NOT NULL,
   PRIMARY KEY(id_camp),
   FOREIGN KEY(id_debat) REFERENCES Debat(id_debat)
);

CREATE TABLE Argument(
   id_arg SERIAL,
   date_arg DATE,
   texte VARCHAR(50),
   id_camp INT NOT NULL,
   id_arg_principal INT,
   id_utilisateur INT NOT NULL,
   PRIMARY KEY(id_arg),
   FOREIGN KEY(id_camp) REFERENCES Camp(id_camp),
   FOREIGN KEY(id_arg_principal) REFERENCES Argument(id_arg),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur)
);

CREATE TABLE Voter(
   id_utilisateur INT,
   id_arg INT,
   date_vote DATE,
   PRIMARY KEY(id_utilisateur, id_arg),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur),
   FOREIGN KEY(id_arg) REFERENCES Argument(id_arg)
);

CREATE TABLE Signaler(
   id_utilisateur INT,
   id_arg INT,
   date_signalement DATE,
   PRIMARY KEY(id_utilisateur, id_arg),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur),
   FOREIGN KEY(id_arg) REFERENCES Argument(id_arg)
);

CREATE TABLE Contenir(
   nom_c VARCHAR(50),
   id_debat INT,
   PRIMARY KEY(nom_c, id_debat),
   FOREIGN KEY(nom_c) REFERENCES Categorie(nom_c),
   FOREIGN KEY(id_debat) REFERENCES Debat(id_debat)
);

CREATE TABLE Sanctionner(
   id_utilisateur INT,
   id_arg INT,
   id_moderateur INT,
   date_sanction DATE,
   raison VARCHAR(50),
   est_banis BOOLEAN,
   PRIMARY KEY(id_utilisateur, id_arg, id_moderateur),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur),
   FOREIGN KEY(id_arg) REFERENCES Argument(id_arg),
   FOREIGN KEY(id_moderateur) REFERENCES Utilisateur(id_utilisateur)
);

CREATE TABLE Cacher(
   id_arg INT,
   date_cache DATE,
   id_utilisateur INT NOT NULL,
   PRIMARY KEY(id_arg),
   FOREIGN KEY(id_arg) REFERENCES Argument(id_arg),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur)
);

CREATE TABLE Valider(
   id_arg INT,
   date_validation DATE,
   id_utilisateur INT NOT NULL,
   PRIMARY KEY(id_arg),
   FOREIGN KEY(id_arg) REFERENCES Argument(id_arg),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur)
);
