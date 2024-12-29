<?php
// Connexion à la base de données
$host = "localhost";
$dbname = "debatarena";
$user = "thay";
$password = "thay";

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id_utilisateur = $_POST['id_utilisateur'];
    $id_arg = $_POST['id_arg'];

    if ($action === 'valider') {
        // Si une sanction est choisie, alors vérifie la raison et insére la sanction
        if (!empty($_POST['type_sanction'])) {
            $type_sanction = $_POST['type_sanction'];
            $raison = isset($_POST['raison']) ? $_POST['raison'] : '';
            $id_moderateur = 1; // ID fictif du modérateur connecté

            // Vérifie si une sanction existe déjà
            $query_check = "SELECT COUNT(*) FROM Sanctionner
                            WHERE id_utilisateur = :id_utilisateur
                            AND id_arg = :id_arg
                            AND id_moderateur = :id_moderateur";
            $stmt_check = $pdo->prepare($query_check);
            $stmt_check->execute([
                'id_utilisateur' => $id_utilisateur,
                'id_arg' => $id_arg,
                'id_moderateur' => $id_moderateur
            ]);

            if ($stmt_check->fetchColumn() == 0) {
                // Insére la sanction
                $query_insert = "INSERT INTO Sanctionner (id_utilisateur, id_arg, id_moderateur, raison, type_sanction)
                                 VALUES (:id_utilisateur, :id_arg, :id_moderateur, :raison, :type_sanction)";
                $stmt_insert = $pdo->prepare($query_insert);
                $stmt_insert->execute([
                    'id_utilisateur' => $id_utilisateur,
                    'id_arg' => $id_arg,
                    'id_moderateur' => $id_moderateur,
                    'raison' => $raison,
                    'type_sanction' => $type_sanction
                ]);
                $message = "Sanction validée avec succès.";
            } else {
                $message = "Cette sanction a déjà été enregistrée.";
            }
        }

        // Supprime le signalement après validation
        $query_delete = "DELETE FROM Signaler WHERE id_utilisateur = :id_utilisateur AND id_arg = :id_arg";
        $stmt_delete = $pdo->prepare($query_delete);
        $stmt_delete->execute(['id_utilisateur' => $id_utilisateur, 'id_arg' => $id_arg]);
        $message = "Signalement validé.";
    } elseif ($action === 'ne_pas_valider') {
        // Supprimer le signalement sans autre action
        $query_delete = "DELETE FROM Signaler WHERE id_utilisateur = :id_utilisateur AND id_arg = :id_arg";
        $stmt_delete = $pdo->prepare($query_delete);
        $stmt_delete->execute(['id_utilisateur' => $id_utilisateur, 'id_arg' => $id_arg]);
    }
}

// On affice les signalements non validés
$query = "SELECT s.id_utilisateur, s.id_arg, s.date_signalement, u.pseudo, a.texte AS argument_texte
          FROM Signaler s
          JOIN Utilisateur u ON s.id_utilisateur = u.id_utilisateur
          JOIN Argument a ON s.id_arg = a.id_arg
          WHERE s.est_valide = FALSE";
$stmt = $pdo->query($query);
$signalements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Signalements</title>
</head>
<body>
    <h1>Gestion des Signalements</h1>

    <!-- Affichage des messages -->
    <?php if (!empty($message)): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <table border="1">
        <thead>
            <tr>
                <th>Pseudo</th>
                <th>Argument Signalé</th>
                <th>Date du Signalement</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($signalements as $signalement): ?>
                <tr>
                    <td><?= htmlspecialchars($signalement['pseudo']) ?></td>
                    <td><?= htmlspecialchars($signalement['argument_texte']) ?></td>
                    <td><?= htmlspecialchars($signalement['date_signalement']) ?></td>
                    <td>
                        <form method="POST" style="display: inline-block;">
                            <input type="hidden" name="id_utilisateur" value="<?= $signalement['id_utilisateur'] ?>">
                            <input type="hidden" name="id_arg" value="<?= $signalement['id_arg'] ?>">
                            <label>Raison :</label>
                            <input type="text" name="raison">
                            <label>Sanction :</label>
                            <select name="type_sanction">
                                <option value="">Sélectionnez</option>
                                <option value="Avertissement">Avertissement</option>
                                <option value="Bannissement">Bannissement</option>
                            </select>
                            <button type="submit" name="action" value="valider">Valider</button>
                        </form>
                        <form method="POST" style="display: inline-block;">
                            <input type="hidden" name="id_utilisateur" value="<?= $signalement['id_utilisateur'] ?>">
                            <input type="hidden" name="id_arg" value="<?= $signalement['id_arg'] ?>">
                            <button type="submit" name="action" value="ne_pas_valider">Ne pas valider</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
