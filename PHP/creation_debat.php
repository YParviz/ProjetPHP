<?php
$host = "localhost";
$host_fac = "postgresql1.ensinfo.sciences.univ-nantes.prive";
$dbname = "l3_alt_02";
$user = "l3_alt_02";
$password = "l3_alt_02";

try {
    // Connexion à PostgreSQL
    $pdo = new PDO("pgsql:host=$host_fac;dbname=$dbname", $user, $password);
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
    $creation = false;
    $erreur = false;

    if($_SERVER['REQUEST_METHOD'] === 'POST'){ //Les champs pour la création sont remplis
        $valeurs['titre'] = $_POST['titre'];
        $valeurs['desc'] = $_POST['description'];
        $valeurs['duree_jour'] = $_POST['duree_jour'];
        $valeurs['duree_heure'] = $_POST['duree_heure'];
        $valeurs['camp1'] = $_POST['camp1'];
        $valeurs['camp2'] = $_POST['camp2'];
        
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

        if(!$erreur){
            //Création du débat
            $stmt = $pdo->prepare("INSERT INTO Debat (nom_d, desc_d, duree, id_utilisateur) VALUES (:nom_d, :desc_d, :duree, :id_utilisateur)");

            $duree = $valeurs['duree_jour'] * 24 + $valeurs['duree_heure'];

            $stmt->execute([
                ':nom_d' => $valeurs['titre'],
                ':desc_d' => $valeurs['desc'],
                ':duree' => $duree,
                ':id_utilisateur' => 1
            ]);


            if($stmt->rowCount() > 0) {

                $stmt = $pdo->prepare("SELECT id_debat FROM Debat WHERE nom_d = :nom_d AND desc_d = :desc_d AND duree = :duree AND id_utilisateur = :utilisateur");
                $stmt->execute([
                    ':nom_d' => $valeurs['titre'],
                    ':desc_d' => $valeurs['desc'],
                    ':duree' => $duree,
                    ':utilisateur' => 1
                ]);

                $debat = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT, 0);

                $stmt_camp = $pdo->prepare("INSERT INTO Camp (nom_camp, id_debat) VALUES (:camp1, :id_debat), (:camp2, :id_debat);");
                $stmt_camp->execute([
                    ':camp1' => $valeurs['camp1'],
                    ':camp2' => $valeurs['camp2'],
                    'id_debat' => $debat['id_debat']
                ]);

                if($stmt_camp->rowCount() > 0){

                    //Pour chaque catégorie : trouver son numéro et insérer dans contenir
                    
                    if($stmt_cat->rowCount() > 1) {
                        $creation = true;
                    } else {
                        $erreurs = "Une erreur s'est produite lors de la création des catégories";
                        $erreur = true;
                    }
                } else {
                    $erreurs = "Une erreur s'est produite lors de la création des camps";
                    $erreur = true;
                }
                
            } else {
                $erreurs = "Une erreur s'est produite lors de la création du débat";
                $erreur;
            }
        }

    } else {
        $valeurs['titre'] = "";
        $valeurs['desc'] = "";
        $valeurs['duree_jour'] = "";
        $valeurs['duree_heure'] = "";
        $valeurs['camp1'] = "";
        $valeurs['camp2'] = "";
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
<?php if(!$creation) {
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
        Camp 1 : <input type="text" name = "camp1" maxlength="100" required value="<?php $camp = $valeurs['camp1']; echo "$camp"?>">
        Camp 2 : <input type="text" name = "camp2" maxlength="100" required value="<?php $camp = $valeurs['camp2']; echo "$camp"?>">

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
<?php } else { ?>
    <p>Le débat a été créé, il est en attente de validation par un modérateur.</p><br>
    <a href="creation_debat.php">Retour</a>
<?php } ?>
</body>
</html>
