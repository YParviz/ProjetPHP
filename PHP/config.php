<?php
$servername = "";
$username = "";
$password = "";
$dbname = "debatarena";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}
?>  echo "Connexion réussie à la base de données.<br>";
}
?>
