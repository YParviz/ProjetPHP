<?php

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
try {
    $pdo = new PDO(
        "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']};charset={$_ENV['DB_CHARSET']}",
        $_ENV['DB_USER'],
        $_ENV['DB_PASSWORD'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Initialisation de FastRoute
$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $r->get('/', 'Controllers\DebatController@listDebats'); // page d'accueil
    $r->get('/debats[/{page:\d+}]', 'Controllers\DebatController@listDebats'); // liste des débats avec pagination
    $r->get('/debat/{id:\d+}', 'Controllers\DebatController@viewDebat'); // page d'un débat spécifique
});

// Récupérer la méthode de la requête HTTP
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Préfixe de base de l'application
$basePath = '/DebatArena/src/web';

// Vérification et retrait du préfixe uniquement si nécessaire
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath)); // Retirer le préfixe
}

// Si l'URI est vide (accueil), la rediriger vers la racine
if (empty($uri)) {
    $uri = '/';
}

// Affichage de la navbar
renderNavbar();

// Dispatcher la requête à l'aide de FastRoute
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);


// Traitement de la réponse en fonction de la route trouvée
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // Si la route n'est pas trouvée, afficher une erreur 404
        http_response_code(404);
        echo "Page non trouvée";
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        // Si la méthode HTTP n'est pas autorisée, afficher une erreur 405
        http_response_code(405);
        echo "Méthode non autorisée";
        break;

    case FastRoute\Dispatcher::FOUND:
        // Si la route est trouvée, appeler le contrôleur et la méthode
        [$class, $method] = explode('@', $routeInfo[1]);
        $vars = $routeInfo[2];


        // Instancier le contrôleur et appeler la méthode correspondante
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
