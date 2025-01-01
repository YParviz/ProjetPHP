<!DOCTYPE html>
<html>
<head>
    <title>Choisir un débat</title>
</head>
<body>
    <form method="GET" action="choisir_camp.php">
        <label for="debat">Débat:</label>
        <select name="debat" id="debat" required>
            <option value="">Sélectionnez un débat</option>
            <?php
            include 'config.php';
            $result = pg_query($conn, "SELECT id_debat, nom_d FROM Debat WHERE statut = 'Valide'");
            while ($row = pg_fetch_assoc($result)) {
                echo "<option value='" . $row['id_debat'] . "'>" . htmlspecialchars($row['nom_d']) . "</option>";
            }
            pg_close($conn);
            ?>
        </select><br><br>
        <input type="submit" value="Suivant">
    </form>
</body>
</html>
