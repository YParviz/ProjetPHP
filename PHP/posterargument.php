<!DOCTYPE html>
<html>
<head>
    <title>Poster un Argument</title>
</head>
<body>
<h2>Poster un Argument</h2>
    <form action="posterargument.php" method="post">
        <!-- Menu déroulant pour choisir le camp du débat -->
        Choisir un camp:
        <select name="camp">
            <option value="1">Camp 1</option>
            <option value="2">Camp 2</option>
        </select><br><br>

        <!-- Espace d'écriture pour l'argument -->
        Commentaire:
        <textarea name="commentaire" required></textarea><br><br>

        <!-- Bouton pour poster le commentaire -->
        <input type="submit" value="Poster">
    </form>
</body>
    
<?php
include 'config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $camp = $_POST['camp'];
    $commentaire = $_POST['commentaire'];
    $id_utilisateur = 1; 
    $id_arg_principal = null; 

    // Vérifier la connexion à la base de données
    if ($conn->connect_error) {
        die("Erreur de connexion: " . $conn->connect_error);
    }

    // Insérer le commentaire dans la table Argument
    $sql = "INSERT INTO Argument (date_arg, texte, id_camp, id_utilisateur, id_arg_principal) VALUES (NOW(), ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erreur de préparation: " . $conn->error);
    }
    $stmt->bind_param("siii", $commentaire, $camp, $id_utilisateur, $id_arg_principal);
    
    // Exécuter la requête et vérifier si elle a réussi
    if ($stmt->execute()) {
        echo "Commentaire posté avec succès";
    } else {
        echo "Erreur lors de l'exécution: " . $stmt->error;
        echo "Erreur SQL: " . $stmt->errno . " - " . $stmt->error;
    }

    // Fermer la déclaration et la connexion
    $stmt->close();
    $conn->close();
} else {
    echo "Le formulaire n'a pas été soumis correctement.";
}
?>

   
