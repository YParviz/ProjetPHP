<?php

require __DIR__ . '/../../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;
use DI\ContainerBuilder;
use Util\Database;

$pdo = Database::connect();
// Initialisation de Faker
$faker = Faker\Factory::create('fr_FR');

// Vider les tables pour éviter les doublons
$pdo->exec("
    SET FOREIGN_KEY_CHECKS = 0;
    TRUNCATE TABLE Statistique;
    TRUNCATE TABLE Valider;
    TRUNCATE TABLE Sanctionner;
    TRUNCATE TABLE Contenir;
    TRUNCATE TABLE Signaler;
    TRUNCATE TABLE Voter;
    TRUNCATE TABLE Argument;
    TRUNCATE TABLE Camp;
    TRUNCATE TABLE Debat;
    TRUNCATE TABLE Categorie;
    TRUNCATE TABLE Utilisateur;
    SET FOREIGN_KEY_CHECKS = 1;
");

// -----------------------------
// Insertion des utilisateurs
// -----------------------------
$roles = ['Utilisateur', 'Moderateur', 'Administrateur'];
$utilisateurs = [];

for ($i = 0; $i < 10; $i++) {
    $email = $faker->unique()->email;
    $pseudo = $faker->unique()->userName;
    $mdp = password_hash($faker->password, PASSWORD_BCRYPT);
    $role = $faker->randomElement($roles);

    $stmt = $pdo->prepare("INSERT INTO Utilisateur (email, pseudo, mdp, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$email, $pseudo, $mdp, $role]);

    $utilisateurs[] = $pdo->lastInsertId();
}

// -----------------------------
// Insertion des catégories
// -----------------------------
$categories = [];

for ($i = 0; $i < 5; $i++) {
    $nom = $faker->unique()->word;
    $desc = $faker->sentence(10);

    $stmt = $pdo->prepare("INSERT INTO Categorie (nom_c, desc_c) VALUES (?, ?)");
    $stmt->execute([$nom, $desc]);

    $categories[] = $pdo->lastInsertId();
}

// -----------------------------
// Insertion des débats
// -----------------------------
$debat_ids = [];

for ($i = 0; $i < 5; $i++) {
    $nom = $faker->sentence(3);
    $desc = $faker->sentence(15);
    $statut = 'Valide';  // Forcer tous les débats à être valides
    $duree = $faker->numberBetween(12, 72);
    $id_utilisateur = $faker->randomElement($utilisateurs);

    $stmt = $pdo->prepare("INSERT INTO Debat (nom_d, desc_d, statut, duree, id_utilisateur) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $desc, $statut, $duree, $id_utilisateur]);

    $debat_ids[] = $pdo->lastInsertId();
}

// -----------------------------
// Insertion des camps
// -----------------------------
$camps = [];

foreach ($debat_ids as $id_debat) {
    for ($i = 0; $i < 2; $i++) {  // Chaque débat a 2 camps
        $nom = $faker->word;

        $stmt = $pdo->prepare("INSERT INTO Camp (nom_camp, id_debat) VALUES (?, ?)");
        $stmt->execute([$nom, $id_debat]);

        $camps[] = $pdo->lastInsertId();
    }
}

// -----------------------------
// Insertion des arguments
// -----------------------------
$arguments = [];

foreach ($camps as $id_camp) {
    for ($i = 0; $i < 5; $i++) {  // Chaque camp a 5 arguments
        $texte = $faker->sentence(12);
        $id_utilisateur = $faker->randomElement($utilisateurs);

        $stmt = $pdo->prepare("INSERT INTO Argument (texte, id_camp, id_utilisateur) VALUES (?, ?, ?)");
        $stmt->execute([$texte, $id_camp, $id_utilisateur]);

        $arguments[] = $pdo->lastInsertId();
    }
}

// -----------------------------
// Voter pour les arguments
// -----------------------------
foreach ($utilisateurs as $id_utilisateur) {
    $nb_votes = $faker->numberBetween(1, 5);

    for ($i = 0; $i < $nb_votes; $i++) {
        $id_arg = $faker->randomElement($arguments);

        $stmt = $pdo->prepare("INSERT IGNORE INTO Voter (id_utilisateur, id_arg) VALUES (?, ?)");
        $stmt->execute([$id_utilisateur, $id_arg]);
    }
}

// -----------------------------
// Signaler des arguments
// -----------------------------
foreach ($utilisateurs as $id_utilisateur) {
    if ($faker->boolean(20)) { // 20% de chances de signaler un argument
        $id_arg = $faker->randomElement($arguments);
        $stmt = $pdo->prepare("INSERT INTO Signaler (id_utilisateur, id_arg) VALUES (?, ?)");
        $stmt->execute([$id_utilisateur, $id_arg]);
    }
}

// -----------------------------
// Associer catégories & débats
// -----------------------------
foreach ($debat_ids as $id_debat) {
    $id_categorie = $faker->randomElement($categories);
    $stmt = $pdo->prepare("INSERT INTO Contenir (id_c, id_debat) VALUES (?, ?)");
    $stmt->execute([$id_categorie, $id_debat]);
}

// -----------------------------
// Sanctionner des arguments
// -----------------------------
foreach ($arguments as $id_arg) {
    if ($faker->boolean(10)) { // 10% de chances d'avoir une sanction
        $id_utilisateur = $faker->randomElement($utilisateurs);
        $raison = $faker->sentence(8);
        $type_sanction = $faker->randomElement(['Avertissement', 'Bannissement']);

        $stmt = $pdo->prepare("INSERT INTO Sanctionner (id_arg, id_utilisateur, raison, type_sanction) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id_arg, $id_utilisateur, $raison, $type_sanction]);
    }
}

// -----------------------------
// Statistiques des débats
// -----------------------------
foreach ($debat_ids as $id_debat) {
    $id_camp_gagnant = $faker->randomElement($camps);
    $nb_participant = count($utilisateurs);
    $nb_vote_camp_1 = $faker->numberBetween(10, 50);
    $nb_vote_camp_2 = $faker->numberBetween(10, 50);
    $nb_vote_moyen = ($nb_vote_camp_1 + $nb_vote_camp_2) / 2;
    $nb_arg_camp_1 = $faker->numberBetween(5, 20);
    $nb_arg_camp_2 = $faker->numberBetween(5, 20);
    $nb_arg_moyen = ($nb_arg_camp_1 + $nb_arg_camp_2) / 2;

    $stmt = $pdo->prepare("INSERT INTO Statistique VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id_debat, $id_camp_gagnant, $nb_participant, $nb_vote_camp_1, $nb_vote_camp_2, $nb_vote_moyen, $nb_arg_camp_1, $nb_arg_camp_2, $nb_arg_moyen]);
}

echo "Base de données remplie avec succès !";
?>
