DROP TABLE Statistiques;
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
DROP TYPE typeSanction;

CREATE TYPE roleUtilisateur AS ENUM('Utilisateur', 'Moderateur', 'Administrateur');
CREATE TYPE typeSanction AS ENUM('Avertissement', 'Banissement');

CREATE TABLE Utilisateur(
   id_utilisateur SERIAL NOT NULL,
   email VARCHAR(320) NOT NULL UNIQUE,
   pseudo VARCHAR(20) NOT NULL UNIQUE,
   mdp VARCHAR(20) NOT NULL,
   role roleUtilisateur NOT NULL DEFAULT 'Utilisateur',
   date_creation DATE NOT NULL DEFAULT CURRENT_DATE,
   PRIMARY KEY(id_utilisateur)
);

CREATE TABLE Categorie(
   id_c SERIAL NOT NULL,
   nom_c VARCHAR(100) NOT NULL UNIQUE,
   desc_c VARCHAR(500) NOT NULL,
   PRIMARY KEY(id_c)
);

CREATE TABLE Debat(
   id_debat SERIAL NOT NULL,
   est_valide BOOLEAN NOT NULL DEFAULT FALSE,
   nom_d VARCHAR(100) NOT NULL,
   desc_d VARCHAR(500) NOT NULL,
   duree INTERVAL NOT NULL,
   date_creation DATE NOT NULL,
   id_utilisateur INT NOT NULL,
   PRIMARY KEY(id_debat),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur)
);

CREATE TABLE Camp(
   id_camp SERIAL NOT NULL,
   nom_camp VARCHAR(100) NOT NULL,
   id_debat INT NOT NULL,
   PRIMARY KEY(id_camp),
   FOREIGN KEY(id_debat) REFERENCES Debat(id_debat)
);

CREATE TABLE Argument(
   id_arg SERIAL NOT NULL,
   date_poste DATE NOT NULL DEFAULT CURRENT_DATE,
   texte VARCHAR(100) NOT NULL,
   id_camp INT NOT NULL,
   id_arg_principal INT,
   id_utilisateur INT NOT NULL,
   PRIMARY KEY(id_arg),
   FOREIGN KEY(id_camp) REFERENCES Camp(id_camp),
   FOREIGN KEY(id_arg_principal) REFERENCES Argument(id_arg),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur)
);

CREATE TABLE Voter(
   id_utilisateur INT NOT NULL,
   id_arg INT NOT NULL,
   date_vote DATE NOT NULL DEFAULT CURRENT_DATE,
   PRIMARY KEY(id_utilisateur, id_arg),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur),
   FOREIGN KEY(id_arg) REFERENCES Argument(id_arg)
);

CREATE TABLE Signaler(
   id_utilisateur INT NOT NULL,
   id_arg INT NOT NULL,
   date_signalement DATE NOT NULL DEFAULT CURRENT_DATE,
   est_valide BOOLEAN NOT NULL DEFAULT FALSE,
   PRIMARY KEY(id_utilisateur, id_arg),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur),
   FOREIGN KEY(id_arg) REFERENCES Argument(id_arg)
);

CREATE TABLE Contenir(
   id_c INT NOT NULL,
   id_debat INT NOT NULL,
   PRIMARY KEY(id_c, id_debat),
   FOREIGN KEY(id_c) REFERENCES Categorie(id_c),
   FOREIGN KEY(id_debat) REFERENCES Debat(id_debat)
);

CREATE TABLE Sanctionner(
   id_utilisateur INT NOT NULL,
   id_arg INT NOT NULL,
   id_moderateur INT NOT NULL,
   date_sanction DATE NOT NULL DEFAULT CURRENT_DATE,
   raison VARCHAR(500),
   type_sanction typeSanction NOT NULL DEFAULT 'Avertissement',
   PRIMARY KEY(id_utilisateur, id_arg, id_moderateur),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur),
   FOREIGN KEY(id_arg) REFERENCES Argument(id_arg),
   FOREIGN KEY(id_moderateur) REFERENCES Utilisateur(id_utilisateur)
);

CREATE TABLE Valider(
   id_arg INT NOT NULL,
   date_validation DATE NOT NULL DEFAULT CURRENT_DATE,
   id_utilisateur INT NOT NULL,
   PRIMARY KEY(id_arg),
   FOREIGN KEY(id_arg) REFERENCES Argument(id_arg),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur)
);

CREATE TABLE Statistiques(
    id_debat INT NOT NULL,
    id_camp_gagnant INT NOT NULL,
    nb_participant INT NOT NULL,
    nb_vote_camp_1 INT NOT NULL,
    nb_vote_camp_2 INT NOT NULL,
    nb_vote_moyen REAL NOT NULL,
    nb_arg_camp_1 INT NOT NULL,
    nb_arg_camp_2 INT NOT NULL,
    nb_arg_moyen REAL NOT NULL,
    PRIMARY KEY(id_debat),
    FOREIGN KEY(id_debat) REFERENCES Debat(id_debat),
    FOREIGN KEY(id_camp_gagnant) REFERENCES Camp(id_camp)
);
