<?php
use DI\ContainerBuilder;
use Controllers\DebatController;
use Models\DebatModel;
use Models\UserStatModel;


$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
    // PDO
    PDO::class => function() {
        return new PDO(
            "{$_ENV['DB_TYPE']}:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']};charset={$_ENV['DB_CHARSET']}",
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    },

    // Dépendances des contrôleurs
    DebatController::class => function ($c) {
        // Injection des modèles nécessaires dans le contrôleur
        $debatModel = $c->get(DebatModel::class);
        $userStatModel = $c->get(UserStatModel::class);
        // Ajoutez ici le conteneur
        return new DebatController($debatModel, $userStatModel, $c);
    },

    // Modèles
    DebatModel::class => function ($c) {
        $pdo = $c->get(PDO::class);
        return new DebatModel($pdo);
    },
    UserStatModel::class => function ($c) {
        $pdo = $c->get(PDO::class);
        return new UserStatModel($pdo);
    },
]);


$container = $containerBuilder->build();
return $container;
