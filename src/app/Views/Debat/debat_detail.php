<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Débat: <?= htmlspecialchars($debat->getName()) ?></title>
</head>
<body>
<div class="container">
    <h1><?= htmlspecialchars($debat->getName()) ?></h1>
    <p><?= htmlspecialchars($debat->getDescription()) ?></p>

    <?php include __DIR__."/../Arguments/list.php" ?>
</div>
</body>
</html>
