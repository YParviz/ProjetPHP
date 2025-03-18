<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Débat: <?= htmlspecialchars($debat->getName()) ?></title>
</head>
<body>
<h1><?= htmlspecialchars($debat->getName()) ?></h1>
<p><?= htmlspecialchars($debat->getDescription()) ?></p>

<!-- Afficher les arguments du débat -->
<?php foreach ($arguments as $argument): ?>
    <div>
        <h3><?= htmlspecialchars($argument->getTitle()) ?></h3>
        <p><?= htmlspecialchars($argument->getContent()) ?></p>
    </div>
<?php endforeach; ?>
</body>
</html>
