<?php
$host = "localhost";
$dbname = "l3_alt_02";
$user = "l3_alt_02";
$password = "l3_alt_02";

try {
    // Connexion à PostgreSQL
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $categories = array();
    $requete = "SELECT * FROM categorie;";
    $pdostmt = $pdo->prepare($requete);
    $pdostmt->execute();
    foreach ($pdostmt->fetchAll(PDO::FETCH_ASSOC) as $categorie) {
        $categories[] = $categorie;
    }

    $valeurs = array();
    $erreurs = "";
    $erreur = false;

    if($_SERVER['REQUEST_METHOD'] === 'POST'){ //Les champs pour la création sont remplis
        $valeurs['titre'] = $_POST['titre'];
        $valeurs['desc'] = $_POST['description'];
        $valeurs['duree_jour'] = $_POST['duree_jour'];
        $valeurs['duree_heure'] = $_POST['duree_heure'];
        
        foreach($categories as $categorie){
            $nomCat = $categorie['nom_c'];
            if(isset($_POST[$nomCat])) {
                $valeurs['cats'][] = $_POST[$nomCat];
            }
        }

        if($valeurs['duree_jour'] == 0){
            $erreurs = $erreurs."La durée minimum d'un débat est de 1 jour<br>";
        }

        if($valeurs['duree_jour'] == 7 && $valeurs['duree_heure'] > 0){
            $erreurs = $erreurs."La durée d'un débat ne doit pas dépasser 7 jours<br>";
        }

        print_r($_POST);

        //TODO : plein de test
        $erreur = $erreur == "";
        $modeCreation = true;
    } else { //Les champs sont vides
        

        $modeCreation = false;
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
<?php if(!$modeCreation) {?>
<form method="POST">
    <p>
        Titre : <input type="text" name="titre" required><br>
        Description : <br>
        <textarea name="description" rows="8" cols="30" required></textarea><br>
        Durée du débat :  <input type="number" min="1" max="7" name="duree_jour" value=1> jours <input type="number" min="0" max="24" name="duree_heure" value=0> heures <br>
        <h2>Catégorie</h2>
        <?php
            foreach ($categories as $categorie) {
                $nom_cat = $categorie['nom_c'];
                echo "$nom_cat : <input type='checkbox' name='$nom_cat' value='a'><br>";
            }
        ?>
        <br>
        <input type="submit">
    </p>
    </form>
<?php } else if($erreur) { 
            echo $erreurs;
    } else { ?>
    <p>Le débat a été créé, il est en attente de validation par un modérateur.</p><br>
    <a href="creation_debat.php">Retour</a>
<?php } ?>
</body>
</html>
