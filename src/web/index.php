<?php


if (file_exists(__DIR__.'/../../vendor/autoload.php')) {
    require __DIR__.'/../../vendor/autoload.php';
} else {
    echo "<h1>Veuillez installer Composer</h1>";
    echo "<p>Pour générer le fichier <i>vendor/autoload.php</i>, executer la commande 
suivante :<pre>composer dump</pre> </a></p>";
    echo "<p>Merci pour votre visite !</p>";
    exit;
}


use Controllers\ArgumentController;
use Models\ArgumentModel;
use Symfony\Component\Dotenv\Dotenv;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Controllers\DebatController;


$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../../.env');

// Création du conteneur d'injection de dépendances
$container = require_once __DIR__ . '/../Container/container.php';

// Initialisation de FastRoute
$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $r->get('/', 'Controllers\DebatController@listDebats'); // page d'accueil
    $r->get('/debats[/{page:\d+}]', 'Controllers\DebatController@listDebats'); // liste des débats avec pagination
    $r->get('/debat/{id:\d+}', 'Controllers\DebatController@viewDebat'); // page d'un débat spécifique

    $r->addRoute(["GET", "POST"], '/debate/{idDebate:\d}/arguments', function ($args) {
        ArgumentController::list($args['idDebate']);
    });
    $r->get('/debate/{idDebate:\d}/poste', function ($args) {
        ArgumentController::create($args['idDebate']);
    });
    $r->post('/debate/{idDebate:\d}/postArg', function ($args) {
        ArgumentController::poste($args['idDebate']);
    });
    $r->post('/vote', function () {
        ArgumentController::vote();
    });
    $r->post('/unvote', function () {
        ArgumentController::unvote();
    });
});

// Je recupère la méthode de la requête HTTP
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Préfixe de base de l'application
$basePath = '/DebatArena/src/web';

// Je vérifie s'il faut retirer le prefix
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath)); // ça retire le préfixe
}

// Redirection vers la racine
if (empty($uri)) {
    $uri = '/';
}


// On dispatch la requête avec FastRoute
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

// Traitement de la réponse en fonction de la route trouvée
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        die('NOT_FOUND');
        // ... 404 Not Found
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        die('Not Allowed');
        break;

    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        print $handler($vars);
        break;
}
