<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Elément de connexion pour la base de donnée pour le projet à mettre dans le .env
$host = "postgresql1.ensinfo.sciences.univ-nantes.prive";
$dbname = "l3_alt_02";
$user = "l3_alt_02";
$password = "l3_alt_02";

$message = ""; // Variable pour stocker le message à afficher

try {
    // Réalise la connection à la base via les variables.
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $email = "";
    $mdp = "";
    $pseudo = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données du formulaire
        $email = $_POST['email'];
        $mdp = $_POST['mdp'];
        $pseudo = $_POST['pseudo'];
    }


    // Requête pour vérifier les emails et mdp
    $stmt = $pdo->prepare("INSERT INTO Utilisateur (email, pseudo, mdp) VALUES (:email, :pseudo, :mdp)");
    $stmt->bindParam(':email', $email); //binParam sécurise les données envoyées dans la requette INSERT
    $stmt->bindParam(':pseudo', $pseudo);
    $stmt->bindParam(':mdp', $mdp);
    $stmt->execute();

    // Récupère le retour de la base sur la réussite ou non de l'oppération
    if ($stmt->rowCount() > 0) {
        $message = "Inscription réussie ! Bienvenue, $email.";
    } else {
        $message = "Email déjà éxistant.";
    }
} catch (PDOException $e) {
    $message = "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h1>Formulaire d'inscription</h1>
    <?php if ($message): ?>
        <p style="color: red;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="email">Adresse mail :</label>
        <input type="text" id="email" name="email" required>
        <br><br>
        <label for="pseudo">Pseudo :</label>
        <input type="text" id="pseudo" name="pseudo" required>
        <br><br>
        <label for="mdp">Mot de passe :</label>
        <input type="password" id="mdp" name="mdp" required>
        <br><br>
        <button type="submit">Valider l'inscritpion</button>
    </form>
</body>
</html>