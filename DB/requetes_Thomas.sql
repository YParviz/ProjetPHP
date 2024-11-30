-- Requête pour créer les comptes des nouveaux utilisateurs
INSERT INTO Utilisateur (email, pseudo, mdp, date_creation) VALUES
('johndoe@example.com', 'JohnDoe', 'password123', CURRENT_DATE);

--Requête pour un utilisateur qui se log sur l’application.
SELECT id_utilisateur, pseudo
FROM Utilisateur
WHERE email = 'johndoe@example.com' AND mdp = 'password123';

-- Requête de modif et suppression de compte pour ces utilisateurs.
UPDATE Utilisateur
SET mdp = 'newpassword'
WHERE id_utilisateur = 1;

DELETE FROM Utilisateur
WHERE id_utilisateur = 1;

--Requête récupération des infos utilisateurs qui est sélectionnée. Qu’il soit en train de consulter son profil ou celui de quelqu’un d’autre.
SELECT id_utilisateur, email, pseudo, date_creation
FROM Utilisateur
WHERE id_utilisateur = 1; -- Remplacer 1 par l'ID de l'utilisateur consulté

--Récupérer le top 10 des débatteurs qui ont le plus de vote sur le mois. (garder dans le rapport)
SELECT U.id_utilisateur, U.pseudo, COUNT(V.id_arg) AS votes_reçus
FROM Utilisateur U
JOIN Argument A ON U.id_utilisateur = A.id_utilisateur
JOIN Voter V ON A.id_arg = V.id_arg
WHERE DATE_PART('month', V.date_vote) = DATE_PART('month', CURRENT_DATE)
  AND DATE_PART('year', V.date_vote) = DATE_PART('year', CURRENT_DATE)
GROUP BY U.id_utilisateur, U.pseudo
ORDER BY votes_reçus DESC
LIMIT 10;

--Récupérer le top 10 des débatteurs qui ont le plus de vote sur l’année. (garder dans le rapport)
SELECT U.id_utilisateur, U.pseudo, COUNT(V.id_arg) AS votes_reçus
FROM Utilisateur U
JOIN Argument A ON U.id_utilisateur = A.id_utilisateur
JOIN Voter V ON A.id_arg = V.id_arg
WHERE DATE_PART('year', V.date_vote) = DATE_PART('year', CURRENT_DATE)
GROUP BY U.id_utilisateur, U.pseudo
ORDER BY votes_reçus DESC
LIMIT 10;



