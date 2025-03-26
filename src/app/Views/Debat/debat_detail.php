<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Débat: <?= htmlspecialchars($debat->getName()) ?></title>
    <link rel="stylesheet" href="/css/navbar.css">
</head>
<body>
<h1><?= htmlspecialchars($debat->getName()) ?></h1>
<p><?= htmlspecialchars($debat->getDescription()) ?></p>

<?php include __DIR__."/../Arguments/list.php" ?>
</body>
</html>
