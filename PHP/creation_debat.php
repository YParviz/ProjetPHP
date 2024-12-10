<?php
$host = "localhost";
$dbname = "l3_alt_02";
$user = "l3_alt_02";
$password = "l3_alt_02";

try {
    // Connexion à PostgreSQL
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $requete = "SELECT * FROM categorie;";
    $res = array();

    $pdostmt = $pdo->prepare($requete);
    $pdostmt->execute();
    foreach ($pdostmt->fetchAll(PDO::FETCH_ASSOC) as $categorie) {
        $res[] = $categorie;
    }

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>DebateArena</title>
    <meta charset="utf-8">
</head>
<body>
<h1>Création d'un débat</h1>
<form>
    <p>
        Titre : <input type="text" name="titre"><br>
        Description : <br>
        <textarea name="description" rows="8" cols="30"></textarea><br>
        Date de fin :  <input type="datetime-local" name="date_fin"><br>
        <h2>Catégorie</h2>
        <?php
            foreach ($res as $categorie) {
                $nom_cat = $categorie['nom_c'];
                echo "$nom_cat : <input type='checkbox' name='$nom_cat'><br>";
            }
        ?>
        <input type="submit">
    </p>
</form>
</body>
</html>
