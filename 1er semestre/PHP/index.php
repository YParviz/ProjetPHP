<?php
$host = "postgresql1.ensinfo.sciences.univ-nantes.prive";
$dbname = "l3_alt_02";
$user = "l3_alt_02";
$password = "l3_alt_02";

try {
    // Connexion à PostgreSQL
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);

    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie à la base de données PostgreSQL.";
} catch (PDOException $e) {
  
    die("Erreur de connexion : " . $e->getMessage());
}
?>