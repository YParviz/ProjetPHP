--Dans ce fichier, vous retrouverez toutes les requêtes réalisées par le groupe Youssef, Firmin, Firdaws, et Thomas concernant leur application Debate Arena.
--Chaque requête sera précédée d'un commentaire expliquant son objectif et sa fonction.

--Requêtes CRUD
--CRUD Débat
--CRUD Utilisateur
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



--Requêtes de modération

-- Arguments cachés d'un débat (id du débat = {$VAR_DEBAT})
SELECT Argument.id_arg, Argument.texte, Camp.nom_camp
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  JOIN Signaler ON Signaler.id_arg = Argument.id_arg
WHERE Debat.id_debat = {$VAR_DEBAT}
  AND Signaler.est_valide;