-- Utilisateur
INSERT INTO Utilisateur (email, pseudo, mdp, role, date_creation) VALUES
('john.doe@example.com', 'johndoe', 'mdp1234','Administrateur', '2024-01-15'),
('alice.smith@example.com', 'alice123', 'alicepassword','Moderateur', '2024-02-20'),
('bob.jones@example.com', 'bobjones', 'bobpass456','Utilisateur', '2024-03-10'),
('carol.white@example.com', 'carolw', 'carolpassword','Utilisateur', '2024-04-05'),
('dave.black@example.com', 'daveb', 'dave1234','Utilisateur', '2024-05-12');

-- Categorie
INSERT INTO Categorie (nom_c, desc_c) VALUES
('Jeux vidéos', 'Une catégorie p)our les débats sur les jeux vidéos'),
('Geek', 'Une catégorie spéciale Geek'),
('Bricolage', 'Une catégorie géniale pour parle du bricolage !');

-- Debat
INSERT INTO Debat (est_valide, nom_d, desc_d, duree, date_creation, id_utilisateur) VALUES
(true, 'Minecraft vs Fortnite', 'Quel jeu ?', '7 days', '2024-11-18', 3),
(false, 'gngnfnj', 'fsdbuifgduigfuiz', '7 days', '2024-11-19', 4);

-- Camp
INSERT INTO Camp (nom_camp, id_debat) VALUES
('Minecraft', 1),
('Fortnite', 1),
('gngn', 2),
('fnj', 2);

-- Argument
INSERT INTO Argument (date_arg, texte, id_camp, id_arg_principal, id_utilisateur) VALUES
('2024-11-18', 'Minecraft est mieux !', 1, NULL, 4),
('2024-11-19', 'non', 2, 1, 5),
('2024-11-20', 'vqds', 2, NULL, 3);

-- Voter
INSERT INTO Voter (id_utilisateur, id_arg, date_vote) VALUES
(1, 1, '2024-11-18'),
(3, 2, '2024-11-19'),
(5, 2, '2024-11-20'),
(4, 3, '2024-11-20');

-- Signaler
INSERT INTO Signaler (id_utilisateur, id_arg, date_signalement) VALUES
(5, 3, '2024-11-20');

-- Contenir
INSERT INTO Contenir (nom_c, id_debat) VALUES
('Jeux vidéos', 1),
('Geek', 1),
('Geek', 2);

-- Sanctionner TODO

--Cacher
INSERT INTO Cacher (id_arg, date_cache, id_utilisateur) VALUES
(3, '2024-11-20', 2);

-- Valider
INSERT INTO Valider (id_arg, date_validation, id_utilisateur) VALUES
(2, '2024-11-20', 1);