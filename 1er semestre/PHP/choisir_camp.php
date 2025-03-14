<!DOCTYPE html>
<html>
<head>
    <title>Choisir un camp et poster un argument</title>
</head>
<body>
    <?php
    include 'config.php';

    if (isset($_GET['debat'])) {
        $debat_id = intval($_GET['debat']);
        $result = pg_query_params($conn, "SELECT id_camp, nom_camp FROM Camp WHERE id_debat = $1", array($debat_id));
        if (pg_num_rows($result) > 0) {
            echo '<form method="POST" action="posterargument.php">';
            echo '<input type="hidden" name="debat" value="' . htmlspecialchars($debat_id) . '">';
            echo '<label for="camp">Camp:</label>';
            echo '<select name="camp" id="camp" required>';
            echo '<option value="">Sélectionnez un camp</option>';
            while ($row = pg_fetch_assoc($result)) {
                echo "<option value='" . $row['id_camp'] . "'>" . htmlspecialchars($row['nom_camp']) . "</option>";
            }
            echo '</select><br><br>';
            echo 'Argument:<br>';
            echo '<textarea name="argument" required></textarea><br><br>';
            echo '<input type="submit" value="Poster">';
            echo '</form>';
        } else {
            echo 'Aucun camp disponible pour ce débat.';
        }
        pg_close($conn);
    } else {
        echo 'Débat non sélectionné.';
    }
    ?>
</body>
</html>
