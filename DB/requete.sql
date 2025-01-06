--Dans ce fichier, vous retrouverez toutes les requêtes réalisées par le groupe Youssef, Firmin, Firdaws, et Thomas concernant leur application Debate Arena.
--Chaque requête sera précédée d'un commentaire expliquant son objectif et sa fonction.

--Requêtes CRUD
--CRUD Débat


--CRUD Utilisateur
-- Requête pour créer les comptes des nouveaux utilisateurs
INSERT INTO Utilisateur (email, pseudo, mdp, role, date_creation) VALUES
('johndoe@example.com', 'JohnDoe', 'password123', 'Utilisateur', CURRENT_DATE);

-- Requête pour un utilisateur qui se log sur l’application
SELECT id_utilisateur, pseudo, email ,role
FROM Utilisateur
WHERE email = 'johndoe@example.com' AND mdp = 'password123';

-- Requête de modification du mot de passe
UPDATE Utilisateur
SET mdp = 'newpassword'
WHERE id_utilisateur = 1;

-- Requête de suppression de compte, Pour cela, il faut d'abord transférer les débats, arguments, votes et signalements liés à 
--l'utilisateur vers l'utilisateur anonyme. Cela permet de conserver les données générées tout en supprimant le compte.

  -- Détacher les débats de l'utilisateur en mettant id_utilisateur à 0 il concerne l'utilisateur anonyme
  UPDATE Debat
  SET id_utilisateur = 0
  WHERE id_utilisateur = 1;

  -- Détacher les arguments de l'utilisateur en mettant id_utilisateur à 0 il concerne l'utilsiateur anonyme
  UPDATE Argument
  SET id_utilisateur = 0
  WHERE id_utilisateur = 1;

  -- Détacher les votes de l'utilisateur en mettant id_utilisateur à 0 il concerne l'utilsiateur anonyme
  UPDATE Voter
  SET id_utilisateur = 0
  WHERE id_utilisateur = 1;

  -- Détacher les signalements de l'utilisateur en mettant id_utilisateur à 0 il concerne l'utilsiateur anonyme
  UPDATE Signaler
  SET id_utilisateur = 0
  WHERE id_utilisateur = 1;

  -- Ensuite, supprimer l'utilisateur
  DELETE FROM Utilisateur
  WHERE id_utilisateur = 1;

-- Requête pour récupérer les informations d'un utilisateur (consultation de profil)
SELECT id_utilisateur, email, pseudo, role, date_creation
FROM Utilisateur
WHERE id_utilisateur = 4; -- Remplacer 4 par l'ID de l'utilisateur consulté

--CRUD Argument



--Requêtes de consultation des arguments pour l'affichage dans un débat

-- Arguments d'un débat trié par date de poste (id du débat = {$VAR_DEBAT})
SELECT Argument.id_arg, Argument.texte, Camp.nom_camp
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  LEFT OUTER JOIN Signaler ON Signaler.id_arg = Argument.id_arg
WHERE Debat.id_debat = {$VAR_DEBAT}
  AND (Signaler.id_arg IS NULL OR NOT Signaler.est_valide) --N'a pas de signalement validé
ORDER BY Argument.date_poste DESC;

-- Les trois arguments les plus votés d'un débat (id du débat = {$VAR_DEBAT})
SELECT Argument.id_arg, texte, nom_camp, COUNT(*)
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  JOIN Voter ON Voter.id_arg = Argument.id_arg
  LEFT OUTER JOIN Signaler ON Signaler.id_arg = Argument.id_arg
WHERE Debat.id_debat = {$VAR_DEBAT}
  AND (Signaler.id_arg IS NULL OR NOT Signaler.est_valide) --N'a pas de signalement validé
GROUP BY Argument.id_arg, Camp.id_camp
ORDER BY COUNT(Voter.id_arg) DESC
LIMIT 3;



--Requêtes de calcul des statistiques d'un débat

-- Calcul de nombre de vote par camp d'un camp (camp gagnant = première entrée) (id du débat = {$VAR_DEBAT})
SELECT Camp.id_camp, Camp.nom_camp, COUNT(*)
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  JOIN Voter ON Voter.id_arg = Argument.id_arg
  LEFT OUTER JOIN Signaler ON Signaler.id_arg = Argument.id_arg
WHERE Debat.id_debat = {$VAR_DEBAT}
  AND (Signaler.id_arg IS NULL OR NOT Signaler.est_valide) --N'a pas de signalement validé
GROUP BY Camp.id_camp
ORDER BY COUNT(Voter.id_arg) DESC;

-- Calcul du nombre de votant d'un débat (id du débat = {$VAR_DEBAT})
SELECT COUNT(DISTINCT Voter.id_utilisateur)
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  JOIN Voter ON Voter.id_arg = Argument.id_arg
  LEFT OUTER JOIN Signaler ON Signaler.id_arg = Argument.id_arg
WHERE Debat.id_debat = {$VAR_DEBAT}
  AND (Signaler.id_arg IS NULL OR NOT Signaler.est_valide); --N'a pas de signalement validé

-- Calcul du nombre d'argument par camp d'un débat (id du débat = {$VAR_DEBAT})
SELECT Camp.id_camp, Camp.nom_camp, COUNT(*)
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  LEFT OUTER JOIN Signaler ON Signaler.id_arg = Argument.id_arg
WHERE Debat.id_debat = {$VAR_DEBAT}
  AND (Signaler.id_arg IS NULL OR NOT Signaler.est_valide) --N'a pas de signalement validé
GROUP BY Camp.id_camp
ORDER BY COUNT(Argument.id_arg) DESC;

-- Calcul du nombre de participants à un débat (id du débat = {$VAR_DEBAT})
SELECT COUNT(DISTINCT Argument.id_utilisateur)
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  LEFT OUTER JOIN Signaler ON Signaler.id_arg = Argument.id_arg
WHERE Debat.id_debat = {$VAR_DEBAT}
  AND (Signaler.id_arg IS NULL OR NOT Signaler.est_valide); --N'a pas de signalement validé



--Requêtes de statistiques générales

-- Nombre de débat groupé par thème
SELECT Categorie.nom_c, Categorie.desc_c, COUNT(*)
FROM Categorie NATURAL JOIN Contenir
  NATURAL JOIN Debat
  WHERE Debat.statut >= 'Valide' -- Permet d'avoir uniquement les débat validé et fermé
GROUP BY Categorie.id_c;

-- Requête pour récupérer le top 10 des débatteurs qui ont le plus de votes sur un mois donnée
SELECT U.id_utilisateur, U.pseudo, COUNT(V.id_arg) AS votes_reçus
FROM Utilisateur U
JOIN Argument A ON U.id_utilisateur = A.id_utilisateur
JOIN Voter V ON A.id_arg = V.id_arg
WHERE DATE_PART('month', V.date_vote) = 5 -- Remplacer 5 (mai) par le mois donné
  AND EXTRACT(YEAR FROM V.date_vote) = 2024 -- Remplacer 2024 par l'année donnée
GROUP BY U.id_utilisateur, U.pseudo
ORDER BY votes_reçus DESC
LIMIT 10;

-- Requête pour récupérer le top 10 des débatteurs qui ont le plus de votes sur l'année
SELECT U.id_utilisateur, U.pseudo, COUNT(V.id_arg) AS votes_reçus
FROM Utilisateur U
JOIN Argument A ON U.id_utilisateur = A.id_utilisateur
JOIN Voter V ON A.id_arg = V.id_arg
WHERE EXTRACT(YEAR FROM V.date_vote) = EXTRACT(YEAR FROM CURRENT_DATE)
GROUP BY U.id_utilisateur, U.pseudo
ORDER BY votes_reçus DESC
LIMIT 10;


--Requêtes de modération

-- Arguments cachés d'un débat (id du débat = {$VAR_DEBAT})
SELECT Argument.id_arg, Argument.texte, Camp.nom_camp
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  JOIN Signaler ON Signaler.id_arg = Argument.id_arg
WHERE Debat.id_debat = {$VAR_DEBAT}
  AND Signaler.est_valide;


--Un modérateur doit pouvoir accéder à la liste des utilisateurs bannis.

select utilisateur.id_utilisateur
from utilisateur
inner join sanctionner on utilisateur.id_utilisateur = sanctionner.id_utilisateur
where est_banis = 1 ;

--Un modérateur doit pouvoir accéder à la liste des utilisateurs bannis.

select utilisateur.id_utilisateur
from utilisateur
inner join sanctionner on utilisateur.id_utilisateur = sanctionner.id_utilisateur
where est_banis = 1 ;

--Il doit également pouvoir accéder aux sanctions qui concernent un utilisateur, si le compte de cet utilisateur est en étude afin d’être banni.
select sanctionner.est_banis, sanctionner.raison, utilisateur.id_utilisateur
from utilisateur
inner join sanctionner on utilisateur.id_utilisateur=sanctionner.id_utilisateur;

--La liste des sanctions faites par un modérateur est historisée
select sanctionner.est_banis, sanctionner.raison
from sanctionner
inner join moderateur on sanctionner.id_utilisateur=moderateur.id_utilisateur;

--Voir tous les signalements non validés
SELECT signaler.id_arg
from signaler
where signaler.id_arg not in (select id_arg
                              from valider);

--Liste des débats non validés.
SELECT debat.desc_d, debat.date_creation
from debat
where statut = 'Attente';

--Liste des sous-arguments qui dépasse l’argument principal en nombre de votes pour le valider.
SELECT argument.id_arg, argument.texte, argument.id_camp
from argument
inner join valider on argument.id_arg=valider.id_arg
inner join voter on valider.id_arg=voter.id_arg
where ?je bloque


SELECT A1.id_arg
FROM Argument A1 JOIN Voter Vo1 ON A1.id_arg = Vo1.id_arg
                LEFT OUTER JOIN Valider Va ON A1.id_arg = Va.id_arg
                LEFT OUTER JOIN Signaler S ON A1.id_arg = S.id_arg 
WHERE Va.id_arg IS NULL -- N'est pas déjà validé
AND (S.id_arg IS NULL OR NOT S.est_valide) -- N'a pas de signalement validé
AND A1.id_arg_principal IS NOT NULL -- Est un sous-argument
GROUP BY A1.id_arg
HAVING COUNT(*) > (
    SELECT COUNT(*)
    FROM Argument A2 JOIN Voter Vo2 ON A2.id_arg = Vo2.id_arg
    WHERE A2.id_arg = A1.id_arg_principal -- Lien avec le premier argument
    GROUP BY A2.id_arg
);

--Administrateur

--CRUD(Récupération, suppression, ajout, modif) sur la liste des modérateurs.
--Création :
INSERT INTO utilisateur (date_création,email,id_utilisateur,mdp,pseudo,role) VALUES ('02/02/2020','mimi@gmail.com',6,'1234abcd','mimi123','3')

--Lecture :
Select * 
from utilisateur
where role = 3;

--Modification d'un pseudo :
Update utilisateur
set pseudo = 'mumu'
where id-utilisateur = 6;

--Suppression d'un utilisateur :
Delete from utilisateur
where id_utilisateur = 6;

--CRUD(Récupération, suppression, ajout, modif) sur la liste des catégories.
--Création : 
INSERT INTO categorie (desc_c,nom_c) VALUES ('plantes tropicales','Plantes')

--Lecture :
Select * 
from categorie ;

--Modification d'un pseudo :
Update categorie
set desc_c = 'plantes des tropiques'
where desc_c like 'plantes tropicales';

--Suppression :
Delete from categorie
where desc_c like 'plantes des tropiques';
