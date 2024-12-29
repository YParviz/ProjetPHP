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

-- Requête pour récupérer le top 10 des débatteurs qui ont le plus de votes sur le mois
SELECT U.id_utilisateur, U.pseudo, COUNT(V.id_arg) AS votes_reçus
FROM Utilisateur U
JOIN Argument A ON U.id_utilisateur = A.id_utilisateur
JOIN Voter V ON A.id_arg = V.id_arg
WHERE DATE_PART('month', V.date_vote) = EXTRACT(MONTH FROM CURRENT_DATE)
  AND EXTRACT(YEAR FROM V.date_vote) = EXTRACT(YEAR FROM CURRENT_DATE)
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
