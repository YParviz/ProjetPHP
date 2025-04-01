# Debate Arena

Il s'agit du projet DebateArena développé par Firmin EON, Thomas HAY et Youssef PARVIZ.

Pour tester ce projet, il vous faut :
- une installation PHP avec le PDO pour MySQL
- une installation de composer correspondant à la version de PHP
- un serveur de base donnée MySQL

## Initialisation de la base de données
Le script de création des tables est disponible avec le fichier `DB/tables.sql`.
Le script d'initialisation des données générés par IA lors du premier semestre est disponible avec le fichier `DB/donnees.sql`.

## Initialisation des dépendances PHP
Pour installer correctement toutes les dépendances nécessaires au projet, il faut faire les deux commandes suivante :
- `composer install`
- `composer dump`

## Tester le projet
Le fichier [.env](.env) contient les configurations de la connexion à la base de données. Les identifiants de connexion et la base sont configurés comme demandé dans le sujet.
Le fichier [php.sh](php.sh) permet de lancer une serveur PHP local sur le port 8080 dans le dossier web. Le site est accessible via le lien https://localhost:8080.

## Arborescence
Le dossier `DB` contient les scripts pour la base de données réalisés au premier semestre.

Le dossier `src` contient les sources de l'application. Dans ce dossier, le dossier `app` contient la logique de l'application ainsi que les vues. Le dossier `Container` propose une implémentation du package PHP-DI. Le dossier `util` contient des utilitaires nécessaires à notre application. Le dossier `web` contient tous les contenus exposés à l'utilisateur (images, CSS, JavaScript).