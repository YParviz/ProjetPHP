<?php
include 'config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $debat = $_POST['debat'];
    $camp = $_POST['camp'];
    $argument = $_POST['argument'];
    $id_utilisateur = 1; // Utilisateur codé en dur
    $id_arg_principal = null; // Valeur par défaut pour id_arg_principal

    // Ajouter des messages de débogage
    echo "Débat: " . htmlspecialchars($debat) . "<br>";
    echo "Camp: " . htmlspecialchars($camp) . "<br>";
    echo "Argument: " . htmlspecialchars($argument) . "<br>";
    echo "ID Utilisateur: " . $id_utilisateur . "<br>";

    // Insérer l'argument dans la table Argument
    $sql = "INSERT INTO Argument (date_poste, texte, id_camp, id_utilisateur, id_arg_principal) VALUES (NOW(), $1, $2, $3, $4)";
    $result = pg_query_params($conn, $sql, array($argument, $camp, $id_utilisateur, $id_arg_principal));
    
    // Vérifier si la requête a réussi
    if ($result) {
        echo "Argument posté avec succès pour le camp et le débat sélectionnés.";
    } else {
        echo "Erreur lors de l'exécution: " . pg_last_error($conn);
    }

    // Fermer la connexion
    pg_close($conn);
} else {
    echo "Le formulaire n'a pas été soumis correctement.";
}
?>
