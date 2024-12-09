-- Utilisateurs
INSERT INTO Utilisateur (email, pseudo, mdp, role, date_creation) VALUES
('jean.dupont@email.com', 'JeanD', 'mdp123', 'Utilisateur', '2024-01-15'),
('marie.bernard@email.com', 'MariB', 'securite456', 'Moderateur', '2024-01-20'),
('pierre.martin@email.com', 'PierreM', 'admin789', 'Administrateur', '2024-01-25'),
('sophie.dupuis@email.com', 'SophieD', 'modo2023', 'Moderateur', '2024-02-01'),
('lucas.roux@email.com', 'LucasR', 'moderateur456', 'Moderateur', '2024-02-10'),
('thomas.durand@email.com', 'ThomasD', 'password123', 'Utilisateur', '2024-02-15'),
('emma.richard@email.com', 'EmmaR', 'secure789', 'Utilisateur', '2024-02-20'),
('julie.rousseau@email.com', 'JulieR', 'debate2024', 'Moderateur', '2024-03-01'),
('nicolas.petit@email.com', 'NicolasP', 'admin2024', 'Administrateur', '2024-03-05'),
('clara.moreau@email.com', 'ClaraM', 'modo2024', 'Moderateur', '2024-03-10');

-- Insertion de catégories
INSERT INTO Categorie (nom_c, desc_c) VALUES
('Politique', 'Débats sur des sujets politiques nationaux et internationaux'),
('Environnement', 'Questions écologiques et changement climatique'),
('Technologie', 'Innovations et avancées technologiques'),
('Société', 'Enjeux sociétaux et sociaux contemporains'),
('Économie', 'Débats économiques et modèles économiques'),
('Éducation', 'Systèmes éducatifs et pédagogie'),
('Santé', 'Questions de santé publique et systèmes de soins');

-- Insertion de débats
INSERT INTO Debat (est_valide, nom_d, desc_d, duree, date_creation, id_utilisateur) VALUES
(true, 'Intelligence Artificielle', 'Faut-il réguler le développement de l''IA ?', '3 days', '2024-04-01', 1),
(true, 'Changement Climatique', 'Quelles actions concrètes pour lutter contre le réchauffement ?', '5 days', '2024-04-10', 2),
(true, 'Éducation Numérique', 'La transformation digitale de l''enseignement', '4 days', '2024-04-15', 6),
(false, 'Économie Collaborative', 'Opportunités et défis des nouvelles formes d''économie', '3 days', '2024-04-20', 7);

-- Lien entre catégories et débats
INSERT INTO Contenir (id_c, id_debat) VALUES
(3, 1),
(2, 2),
(3, 3),
(6, 3),
(5, 4);

-- Insertion de camps
INSERT INTO Camp (nom_camp, id_debat) VALUES
('Pour la régulation de l''IA', 1),
('Contre la régulation de l''IA', 1),
('Alarmistes climatiques', 2),
('Optimistes climatiques', 2),
('Pour l''éducation numérique', 3),
('Contre l''éducation numérique', 3),
('Partisans de l''économie collaborative', 4),
('Critiques de l''économie collaborative', 4);

-- Insertion d'arguments plus nombreux et détaillés
INSERT INTO Argument (texte, id_camp, id_arg_principal, id_utilisateur, date_poste) VALUES
-- Débat Intelligence Artificielle (Camps 1 et 2)
('L''IA non régulée pourrait représenter un risque existentiel', 1, NULL, 1, '2024-04-02'),
('La régulation freinerait l''innovation technologique', 2, NULL, 2, '2024-04-03'),
('Nécessité d''un cadre éthique strict pour l''IA', 1, 1, 3, '2024-04-04'),
('Les algorithmes actuels manquent de transparence', 1, NULL, 4, '2024-04-05'),
('Une régulation excessive pourrait bloquer les start-ups', 2, NULL, 5, '2024-04-06'),
('Risque de manipulation massive des données personnelles', 1, NULL, 6, '2024-04-07'),
('La créativité technologique serait bridée par trop de contraintes', 2, NULL, 7, '2024-04-08'),
('Protéger les droits humains face à l''IA autonome', 1, NULL, 8, '2024-04-09'),
('L''autorégulation par les entreprises technologiques est suffisante', 2, NULL, 9, '2024-04-10'),

-- Débat Changement Climatique (Camps 3 et 4)
('Le réchauffement climatique est une menace réelle', 3, NULL, 2, '2024-04-11'),
('Les mesures actuelles sont insuffisantes', 3, 10, 1, '2024-04-12'),
('L''impact économique des mesures climatiques est trop élevé', 4, NULL, 3, '2024-04-13'),
('Nécessité de réduire drastiquement les émissions de CO2', 3, NULL, 4, '2024-04-14'),
('Les technologies vertes peuvent sauver la planète', 3, NULL, 5, '2024-04-15'),
('L''hystérie climatique nuit au développement économique', 4, NULL, 6, '2024-04-16'),
('Investir massivement dans les énergies renouvelables', 3, NULL, 7, '2024-04-17'),
('Les pays en développement ne peuvent pas supporter ces contraintes', 4, NULL, 8, '2024-04-18'),
('Urgence de protéger la biodiversité', 3, NULL, 9, '2024-04-19'),

-- Débat Éducation Numérique (Camps 5 et 6)
('La digitalisation améliore l''apprentissage', 5, NULL, 6, '2024-04-20'),
('Les interactions humaines restent essentielles', 6, NULL, 7, '2024-04-21'),
('Personnalisation de l''apprentissage grâce au numérique', 5, NULL, 1, '2024-04-22'),
('Risque d''isolement social des étudiants', 6, NULL, 2, '2024-04-23'),
('Développement des compétences numériques', 5, NULL, 3, '2024-04-24'),
('Coût prohibitif de l''équipement numérique', 6, NULL, 4, '2024-04-25'),
('Accessibilité accrue à l''éducation', 5, NULL, 5, '2024-04-26'),
('Vulnérabilité face aux cybermenaces', 6, NULL, 8, '2024-04-27'),
('Formation continue facilitée par le numérique', 5, NULL, 9, '2024-04-28'),

-- Débat Économie Collaborative (Camps 7 et 8)
('L''économie collaborative crée de nouvelles opportunités', 7, NULL, 7, '2024-04-29'),
('Risque de précarisation des travailleurs', 8, NULL, 6, '2024-04-30'),
('Démocratisation de l''accès aux services', 7, NULL, 1, '2024-05-01'),
('Contournement de la réglementation du travail', 8, NULL, 2, '2024-05-02'),
('Réduction des coûts pour les consommateurs', 7, NULL, 3, '2024-05-03'),
('Concurrence déloyale avec les entreprises traditionnelles', 8, NULL, 4, '2024-05-04'),
('Développement de l''économie du partage', 7, NULL, 5, '2024-05-05'),
('Manque de protection sociale pour les travailleurs', 8, NULL, 8, '2024-05-06'),
('Innovation et créativité économique', 7, NULL, 9, '2024-05-07');

-- Votes
INSERT INTO Voter (id_utilisateur, id_arg, date_vote) VALUES
-- Votes pour le débat Intelligence Artificielle (Arguments 1-9)
(1, 1, '2024-04-05'),
(2, 2, '2024-04-06'),
(3, 3, '2024-04-07'),
(4, 4, '2024-04-08'),
(5, 5, '2024-04-09'),
(6, 6, '2024-04-10'),
(7, 7, '2024-04-11'),
(8, 8, '2024-04-12'),
(9, 9, '2024-04-13'),

-- Votes supplémentaires pour l'IA
(2, 1, '2024-04-14'),
(3, 4, '2024-04-15'),
(4, 6, '2024-04-16'),
(5, 8, '2024-04-17'),
(6, 2, '2024-04-18'),
(7, 5, '2024-04-19'),
(8, 7, '2024-04-20'),
(9, 3, '2024-04-21'),

-- Votes pour le débat Changement Climatique (Arguments 10-18)
(1, 10, '2024-04-22'),
(2, 11, '2024-04-23'),
(3, 12, '2024-04-24'),
(4, 13, '2024-04-25'),
(5, 14, '2024-04-26'),
(6, 15, '2024-04-27'),
(7, 16, '2024-04-28'),
(8, 17, '2024-04-29'),
(9, 18, '2024-04-30'),

-- Votes supplémentaires pour Changement Climatique
(2, 10, '2024-05-01'),
(3, 13, '2024-05-02'),
(4, 15, '2024-05-03'),
(5, 17, '2024-05-04'),
(6, 11, '2024-05-05'),
(7, 14, '2024-05-06'),
(8, 16, '2024-05-07'),
(9, 12, '2024-05-08'),

-- Votes pour Éducation Numérique (Arguments 19-27)
(1, 19, '2024-05-09'),
(2, 20, '2024-05-10'),
(3, 21, '2024-05-11'),
(4, 22, '2024-05-12'),
(5, 23, '2024-05-13'),
(6, 24, '2024-05-14'),
(7, 25, '2024-05-15'),
(8, 26, '2024-05-16'),
(9, 27, '2024-05-17'),

-- Votes supplémentaires pour Éducation Numérique
(2, 19, '2024-05-18'),
(3, 22, '2024-05-19'),
(4, 24, '2024-05-20'),
(5, 26, '2024-05-21'),
(6, 20, '2024-05-22'),
(7, 23, '2024-05-23'),
(8, 25, '2024-05-24'),
(9, 21, '2024-05-25'),

-- Votes pour Économie Collaborative (Arguments 28-36)
(1, 28, '2024-05-26'),
(2, 29, '2024-05-27'),
(3, 30, '2024-05-28'),
(4, 31, '2024-05-29'),
(5, 32, '2024-05-30'),
(6, 33, '2024-05-31'),
(7, 34, '2024-06-01'),
(8, 35, '2024-06-02'),
(9, 36, '2024-06-03'),

-- Votes supplémentaires pour Économie Collaborative
(2, 28, '2024-06-04'),
(3, 31, '2024-06-05'),
(4, 33, '2024-06-06'),
(5, 35, '2024-06-07'),
(6, 29, '2024-06-08'),
(7, 32, '2024-06-09'),
(8, 34, '2024-06-10'),
(9, 30, '2024-06-11');

-- Signalements
INSERT INTO Signaler (id_utilisateur, id_arg, date_signalement, est_valide) VALUES
(1, 3, '2024-04-07', false),
(2, 5, '2024-04-15', true),
(3, 8, '2024-04-25', false),
(4, 15, '2024-04-26', false),
(5, 22, '2024-04-29', true),
(6, 31, '2024-05-02', false);

-- Sanctions
INSERT INTO Sanctionner (id_utilisateur, id_arg, id_moderateur, raison, type_sanction) VALUES
(4, 15, 5, 'Contenu potentiellement inexact sur le changement climatique', 'Avertissement'),
(5, 22, 2, 'Propos critiques jugés excessifs sur l''éducation numérique', 'Avertissement'),
(6, 31, 7, 'Argument considéré comme provocateur sur l''économie collaborative', 'Banissement');


-- Valider
INSERT INTO Valider (id_arg, id_utilisateur, date_validation) VALUES
(4, 4, '2024-04-14');