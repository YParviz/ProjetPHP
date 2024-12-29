-- Débats
-- Tous les débats
SELECT Debat.id_debat, Debat.nom_d
FROM Debat
WHERE Debat.statut = 'Valide';

-- Débat par nom
SELECT id_debat, nom_d
FROM Debat
WHERE Debat.nom_d LIKE '%{$VAR}%';

-- Calcul des stats (Requêtes pour avoir les nombres nécessaire au calcul)
-- Calcul du nombre de participants
SELECT COUNT(DISTINCT Argument.id_utilisateur)
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  LEFT OUTER JOIN Signaler ON Signaler.id_arg = Argument.id_arg
WHERE Debat.id_debat = {$VAR_DEBAT}
  AND (Signaler.id_arg IS NULL OR NOT Signaler.est_valide);

-- Calcul de nombre de vote par camp (camp gagnant = première entrée)
SELECT Camp.id_camp, Camp.nom_camp, COUNT(*)
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  JOIN Voter ON Voter.id_arg = Argument.id_arg
  LEFT OUTER JOIN Signaler ON Signaler.id_arg = Argument.id_arg
WHERE Debat.id_debat = {$VAR_DEBAT}
  AND (Signaler.id_arg IS NULL OR NOT Signaler.est_valide)
GROUP BY Camp.id_camp
ORDER BY COUNT(Voter.id_arg) DESC;

-- Calcul du nombre de votant
SELECT COUNT(DISTINCT Voter.id_utilisateur)
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  JOIN Voter ON Voter.id_arg = Argument.id_arg
  LEFT OUTER JOIN Signaler ON Signaler.id_arg = Argument.id_arg
WHERE Debat.id_debat = {$VAR_DEBAT}
  AND (Signaler.id_arg IS NULL OR NOT Signaler.est_valide);

-- Calcul du nombre d'argument par camp
SELECT Camp.id_camp, Camp.nom_camp, COUNT(*)
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  LEFT OUTER JOIN Signaler ON Signaler.id_arg = Argument.id_arg
WHERE Debat.id_debat = {$VAR_DEBAT}
  AND (Signaler.id_arg IS NULL OR NOT Signaler.est_valide)
GROUP BY Camp.id_camp
ORDER BY COUNT(Argument.id_arg) DESC;

-- Calcul du nombre d'utilisateur ayant posté un argument
SELECT COUNT(DISTINCT Argument.id_utilisateur)
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  LEFT OUTER JOIN Signaler ON Signaler.id_arg = Argument.id_arg
WHERE Debat.id_debat = {$VAR_DEBAT}
  AND (Signaler.id_arg IS NULL OR NOT Signaler.est_valide);


-- Arguments cachés d'un débat
SELECT Argument.id_arg, Argument.texte, Camp.nom_camp
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  JOIN Signaler ON Signaler.id_arg = Argument.id_arg
WHERE Debat.id_debat = {$VAR_DEBAT}
  AND Signaler.est_valide;

-- Arguments d'un débat trié
SELECT Argument.id_arg, Argument.texte, Camp.nom_camp
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  LEFT OUTER JOIN Signaler ON Signaler.id_arg = Argument.id_arg
WHERE Debat.id_debat = {$VAR_DEBAT}
  AND (Signaler.id_arg IS NULL OR NOT Signaler.est_valide)
ORDER BY Argument.date_poste DESC;

-- Arguments les plus votés
SELECT Argument.id_arg, texte, nom_camp, COUNT(*)
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  JOIN Voter ON Voter.id_arg = Argument.id_arg
  LEFT OUTER JOIN Signaler ON Signaler.id_arg = Argument.id_arg
WHERE Debat.id_debat = {$VAR_DEBAT}
  AND (Signaler.id_arg IS NULL OR NOT Signaler.est_valide)
GROUP BY Argument.id_arg, Camp.id_camp
ORDER BY COUNT(Voter.id_arg) DESC
LIMIT 3;


-- Statistiques
-- Argument le plus voté pour un débat
SELECT Argument.id_arg, Argument.texte, COUNT(*)
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  JOIN Voter ON Voter.id_arg = Argument.id_arg
  LEFT OUTER JOIN Signaler ON Signaler.id_arg = Argument.id_arg
WHERE Debat.id_debat = {$VAR_DEBAT}
  AND (Signaler.id_arg IS NULL OR NOT Signaler.est_valide)
GROUP BY Argument.id_arg
HAVING COUNT(*) >= ALL (
  SELECT COUNT(*)
  FROM Debat NATURAL JOIN Camp
    JOIN Argument ON Argument.id_camp = Camp.id_camp
    JOIN Voter ON Voter.id_arg = Argument.id_arg
    LEFT OUTER JOIN Signaler ON Signaler.id_arg = Argument.id_arg
  WHERE Debat.id_debat = {$VAR_DEBAT}
  AND (Signaler.id_arg IS NULL OR NOT Signaler.est_valide)
  GROUP BY Argument.id_arg
);


-- Nombre de débat groupé par thème
SELECT Categorie.nom_c, Categorie.desc_c, COUNT(*)
FROM Categorie NATURAL JOIN Contenir
  NATURAL JOIN Debat
  WHERE Debat.statut >= 'Valide' -- Permet d'avoir uniquement les débat validé et fermé
GROUP BY Categorie.id_c;