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
SELECT Argument.id_arg, texte, nom_camp
FROM Debat NATURAL JOIN Camp
          NATURAL JOIN Argument
          JOIN Sanctionner ON Sanctionner.id_arg = Argument.id_arg
WHERE Debat.id_debat = {$VAR}.

-- Arguments d'un débat trié
SELECT Argument.id_arg, texte, nom_camp
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
WHERE Debat.id_debat = {$VAR}
ORDER BY date_arg DESC;

-- Arguments les plus votés
SELECT Argument.id_arg, texte, nom_camp, COUNT(*)
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
  JOIN Voter ON Voter.id_arg = Argument.id_arg
WHERE Debat.id_debat = 1
GROUP BY Argument.id_arg, Camp.id_camp
ORDER BY COUNT(Voter.id_arg) DESC
LIMIT 3;
