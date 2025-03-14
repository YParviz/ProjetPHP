<?php
$host = "localhost";
$port = "5432";
$dbname = "debatarena";
$user = "";
$password = "";

// Créer une connexion
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Vérifier la connexion
if (!$conn) {
    die("La connexion a échoué: " . pg_last_error());
} else {
    echo "Connexion réussie à la base de données.<br>";
}
?>
