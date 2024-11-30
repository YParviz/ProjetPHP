-- Débats
-- Tous les débats
SELECT Debat.id_debat, Debat.nom_d
FROM Debat LEFT OUTER JOIN Statistiques ON Debat.id_debat = Statistiques.id_debat
WHERE Debat.est_valide
  AND Statistiques.id_debat = NULL;

-- Débat par nom
SELECT id_debat, nom_d
FROM Debat
WHERE Debat.nom_d LIKE '%{$VAR}%';

-- Mise à jour des stats
-- TODO : Faire le calcul des stats puis faire l'insert


-- Arguments cachés d'un débat
SELECT Argument.id_arg, Argument.texte, Camp.nom_camp
FROM Debat NATURAL JOIN Camp
          JOIN Argument ON Argument.id_camp = CAmp.id_camp
          JOIN Sanctionner ON Sanctionner.id_arg = Argument.id_arg
WHERE Debat.id_debat = {$VAR}.

-- Arguments d'un débat trié
SELECT Argument.id_arg, Argument.texte, Camp.nom_camp
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
WHERE Debat.id_debat = {$VAR}
ORDER BY Argument.date_arg DESC;

-- Arguments les plus votés
SELECT Argument.id_arg, texte, nom_camp, COUNT(*)
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  JOIN Voter ON Voter.id_arg = Argument.id_arg
WHERE Debat.id_debat = {$VAR}
GROUP BY Argument.id_arg, Camp.id_camp
ORDER BY COUNT(Voter.id_arg) DESC
LIMIT 3;


-- Statistiques
-- Nombre de débat participés avec des arguments
SELECT COUNT(Debat.id_debat)
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  JOIN Utilisateur ON Utilisateur.id_utilisateur = Argument.id_utilisateur
WHERE Utilisateur.id_utilisateur = {$VAR}
GROUP BY Utilisateur.id_utilisateur;

-- Nombre de vote par camp du débat
SELECT Camp.nom_camp, COUNT(*)
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  JOIN Voter ON Voter.id_arg = Argument.id_arg
  WHERE Debat.id_debat = {$VAR}
GROUP BY Camp.id_camp;

-- Argument le plus voté pour un débat
SELECT Argument.id_arg, Argument.texte
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  JOIN Voter ON Voter.id_arg = Argument.id_arg
WHERE Debat.id_debat = 1
GROUP BY Argument.id_arg
HAVING COUNT(*) >= ALL (
  SELECT COUNT(*)
  FROM Debat NATURAL JOIN Camp
    JOIN Argument ON Argument.id_camp = Camp.id_camp
    JOIN Voter ON Voter.id_arg = Argument.id_arg
  WHERE Debat.id_debat = 1
  GROUP BY Argument.id_arg
);

-- Nombre d'argument par camp du débat
SELECT Camp.nom_camp, COUNT(*)
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  WHERE Debat.id_debat = {$VAR}
GROUP BY Camp.id_camp;

-- Nombre de débat groupé par thème
SELECT Categorie.nom_c, Categorie.desc_c, COUNT(*)
FROM Categorie NATURAL JOIN Contenir
  NATURAL JOIN Debat
GROUP BY Categorie.id_c;