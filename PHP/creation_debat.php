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
    $modeCreation = false;
    $erreur = false;

    if($_SERVER['REQUEST_METHOD'] === 'POST'){ //Les champs pour la création sont remplis
        $valeurs['titre'] = $_POST['titre'];
        $valeurs['desc'] = $_POST['description'];
        $valeurs['duree_jour'] = $_POST['duree_jour'];
        $valeurs['duree_heure'] = $_POST['duree_heure'];
        
        foreach($categories as $categorie){
            $nomCat = $categorie['nom_c'];
            if(isset($_POST[$nomCat])) {
                $valeurs['cats'][$nomCat] = $_POST[$nomCat];
            }
        }

        if($valeurs['duree_jour'] == 7 && $valeurs['duree_heure'] > 0){
            $erreurs = $erreurs."La durée d'un débat ne doit pas dépasser 7 jours<br>";
            $erreur = true;
        }

        if(!isset($valeurs['cats'])){
            $erreurs = $erreurs."Le débat doit avoir au minimum une catégorie<br>";
            $erreur = true;
        }

        $modeCreation = !$erreur;

    } else {
        $valeurs['titre'] = "";
        $valeurs['desc'] = "";
        $valeurs['duree_jour'] = "";
        $valeurs['duree_heure'] = "";
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
<?php if(!$modeCreation) {
        if($erreur) {
            echo "<p style='color: crimson'>$erreurs</p>";
        }?>
<form method="POST">
    <p>
        Titre : <input type="text" name="titre" maxlength="100" required value="<?php $titre = $valeurs['titre']; echo "$titre"?>"><br>
        Description : <br>
        <textarea name="description" rows="8" cols="30" maxlength="500" required><?php $desc = $valeurs['desc']; echo "$desc"?></textarea><br>
        Durée du débat :  <input type="number" min="1" max="7" name="duree_jour" value=1> jours <input type="number" min="0" max="24" name="duree_heure" value=0> heures <br>

        <h2>Camp</h2>
        Camp 1 : <input type="text" maxlength="100" required> Camp 2 : <input type="text" maxlength="100" required>

        <h2>Catégorie</h2>
        <?php
            foreach ($categories as $categorie) {
                $nom_cat = $categorie['nom_c'];
                $input = "$nom_cat : <input type='checkbox' name='$nom_cat'";
                if(isset($valeurs['cats'][$nom_cat])) {
                    $input = $input." checked";
                }
                $input = $input."><br>";
                echo $input;
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
