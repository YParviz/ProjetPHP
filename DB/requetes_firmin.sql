-- Débats
-- Tous les débats
SELECT *
FROM Debat
WHERE Debat.est_valide; -- TODO : comment savoir qu'il est fermé ? Jointure externe ?

-- Débat par nom
SELECT *
FROM Debat
WHERE Debat.nom_d LIKE '%{$VAR}%';

-- Mise à jour des stats
-- TODO : revoir le schéma de la base pour inclure les stats

-- Arguments cachés d'un débat
SELECT *
FROM Debat NATURAL JOIN Camp NATURAL JOIN Argument NATURAL JOIN Sanctionner. -- TODO : noms des champs pour les jointures naturelles ! Compléter la requête

-- Argument d'un débat trié
SELECT *
FROM Debat NATURAL JOIN Camp
  JOIN Argument ON Argument.id_camp = Camp.id_camp
ORDER BY date_poste DESC; -- TODO : revoir le nom du champ dat_arg en date_poste ?
