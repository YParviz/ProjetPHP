<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <link rel="stylesheet" href="/path/to/your/styles.css">
</head>
<body>

    <h1>Bienvenue sur votre profil</h1>

    <div class="profile-info">
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Pseudo:</strong> <?php echo htmlspecialchars($pseudo); ?></p>
        <p><strong>Mot de passe:</strong> <?php echo htmlspecialchars($mdp); ?></p>
        <p><strong>Rôle:</strong> <?php echo htmlspecialchars($role); ?></p>
        <p><strong>Date de création:</strong> <?php echo htmlspecialchars($date_creation); ?></p>
    </div>

    <a href="/logout">Déconnexion</a>

</body>
</html>
