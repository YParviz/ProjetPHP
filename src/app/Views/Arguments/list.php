<!DOCTYPE html>
<html lang="fr-fr">
<head>
    <link rel="stylesheet" type="text/css" href="/css/arguments/styleListArguments.css" />
    <script src="/js/arguments/scriptListArguments.js"></script>
</head>
<body>
    <br>
    <?php if ($hasMoreThan5Args): ?>
        <button class="disabledButtonPost">Poster un nouvel argument</button>
        <p class="aide">Vous avez déjà posté le nombre maximum d'argument pour ce débat</p>
    <?php else: ?>
        <button class="buttonPost" onclick="window.location.href='/debate/<?= $debat->getId() ?>/poste'">Poster un nouvel argument</button>
    <?php endif; ?>
    <div class="arguments">
        <div class="camp1">
            <h2 class="center"><?= $camp1->getName() ?></h2>
            <?php foreach ($arguments[0] as $argument) {
                include __DIR__ . "/display.php";
            } ?>
        </div>
        <div class="camp2">
            <h2 class="center"><?= $camp2->getName() ?></h2>
            <?php foreach ($arguments[1] as $argument) {
                include __DIR__ . "/display.php";
            } ?>
        </div>
    </div>
</body>
</html>
