<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Charger les dépendances via Composer
require_once __DIR__ . '/../../vendor/autoload.php';

// Importer la navbar
require_once __DIR__ . '/../app/Views/navbar.php';

use Symfony\Component\Dotenv\Dotenv;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Controllers\DebatController;

// Charger les variables d'environnement
$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../../.env');

// Créer la connexion PDO
$pdo = new PDO(
    "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']};charset={$_ENV['DB_CHARSET']}",
    $_ENV['DB_USER'],
    $_ENV['DB_PASSWORD'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

// Créer le dispatcher avec les routes
$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $r->get('/', 'Controllers\DebatController@listDebats');
    $r->get('/debats', 'Controllers\DebatController@listDebats');
    $r->get('/debatarena/debat/{id:\d+}', 'Controllers\DebatController@viewDebat');  // Route pour afficher un débat spécifique
});

// Récupérer la requête HTTP
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


// Retirer le préfixe "/DebatArena/src/web" pour obtenir l’URL relative
$basePath = '/DebatArena/src/web';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

// Si l'URI est vide, on lui attribue "/"
if ($uri === '') {
    $uri = '/';
}

// Affichage de la navbar
renderNavbar();

// Dispatcher la requête
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);


switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo "Page non trouvée";
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo "Méthode non autorisée";
        break;

    case FastRoute\Dispatcher::FOUND:
        [$class, $method] = explode('@', $routeInfo[1]);
        $vars = $routeInfo[2];

        if (class_exists($class)) {
            $controllerInstance = new $class($pdo);
            if (method_exists($controllerInstance, $method)) {
                call_user_func_array([$controllerInstance, $method], $vars);
            } else {
                http_response_code(500);
                echo "Méthode '$method' introuvable dans le contrôleur '$class'.";
            }
        } else {
            http_response_code(500);
            echo "Contrôleur '$class' introuvable.";
        }
        break;
}
