--Le nombre de débats auquel il a participé avec des arguments. Requête testé

SELECT COUNT(DISTINCT D.id_debat) AS nombre_debats_participes
FROM Argument A
JOIN Camp C ON A.id_camp = C.id_camp
JOIN Debat D ON C.id_debat = D.id_debat
WHERE A.id_utilisateur = 'id_utilisateur';

--Le nombre de votes qu'il a consommé et sur quel débat et ce sur tous les débats(ou le total de votes tous débats confondu)

SELECT D.id_debat, COUNT(V.id_arg) AS nombre_de_votes
FROM Utilisateur U
JOIN Voter V ON U.id_utilisateur = V.id_utilisateur
JOIN Argument A ON V.id_arg = A.id_arg
JOIN Camp C ON A.id_camp = C.id_camp
JOIN Debat D ON C.id_debat = D.id_debat
WHERE U.id_utilisateur = 'id_utilisateur'  
GROUP BY D.id_debat;

--Tous débats confondu :
SELECT COUNT(V.id_arg) AS total_votes
FROM Utilisateur U
JOIN Voter V ON U.id_utilisateur = V.id_utilisateur
WHERE U.id_utilisateur = 'id_utilisateur';

--Historique de ses participations ( ses arguments)

--Si on veut tout l'historique : 
SELECT A.id_arg, A.date_poste, A.texte, D.id_debat, D.nom_d, D.date_creation, C.nom_c
FROM Utilisateur U
JOIN Argument A ON U.id_utilisateur = A.id_utilisateur
JOIN Camp C ON A.id_camp = C.id_camp
JOIN Debat D ON C.id_debat = D.id_debat
WHERE U.id_utilisateur = 'id_utilisateur'
ORDER BY A.date_poste DESC;

--Seulement le texte de l'argument : 
SELECT A.texte
FROM Utilisateur U
JOIN Argument A ON U.id_utilisateur = A.id_utilisateur
WHERE U.id_utilisateur = 'id_utilisateur'
ORDER BY A.date_poste DESC;


--Le nombre de débat remporté par lui même(le nombre de débat où il a commenté dans un seul camp et que ce camp a gagné, si commenté dans les deux pas comptabilisé)

SELECT COUNT(DISTINCT D.id_debat) AS nombre_debats_remportes
FROM Utilisateur U
JOIN Argument A ON U.id_utilisateur = A.id_utilisateur
JOIN Camp C ON A.id_camp = C.id_camp
JOIN Debat D ON C.id_debat = D.id_debat
JOIN Statistiques S ON D.id_debat = S.id_debat
WHERE U.id_utilisateur = 5
AND C.id_camp = S.id_camp_gagnant
AND NOT EXISTS (
      SELECT 1
      FROM Argument A2
      JOIN Camp C2 ON A2.id_camp = C2.id_camp
      WHERE A2.id_utilisateur = U.id_utilisateur
        AND C2.id_debat = D.id_debat
        AND C2.id_camp <> C.id_camp
 );

--le nombre de débat auquel il a participé (argument et vote)

SELECT COUNT(DISTINCT D.id_debat) AS nombre_debats_participes
FROM Debat D
LEFT JOIN Camp C ON D.id_debat = C.id_debat
LEFT JOIN Argument A ON C.id_camp = A.id_camp
LEFT JOIN Voter V ON A.id_arg = V.id_arg
WHERE A.id_utilisateur = 'id_utilisateur' OR V.id_utilisateur = 'id_utilisateur';

--Le nombre de débat créé

SELECT COUNT(*) AS nombre_debats_crees
FROM Debat
WHERE id_utilisateur = 'id_utilisateur';

--Le nombre d'arguments postés

SELECT COUNT(*) AS nombre_arguments_postes
FROM Argument
WHERE id_utilisateur = 'id_utilisateur';

--Le nombre total de votes reçu pour ses propres  arguments

SELECT COUNT(*) AS total_votes_recus
FROM Argument A
JOIN Voter V ON A.id_arg = V.id_arg
WHERE A.id_utilisateur = 'id_utilisateur';
