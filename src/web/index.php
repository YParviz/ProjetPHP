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

//  Importation de la navbar
require_once __DIR__ . '/../app/Views/navbar.php';

use Controllers\ArgumentController;
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
    $r->addRoute(["GET", "POST"], '/argument/{id:\d}', function ($args) {
        $amodel = new \Models\ArgumentModel();
        $argument = $amodel->getById($args['id']);
        $acontroller = new ArgumentController;
        $acontroller->print($argument);
    });

    $r->addRoute(["GET", "POST"], '/debate/{idDebate:\d}/arguments', function ($args) {
        ArgumentController::list($args['idDebate']);
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

// ça affiche la navbar
renderNavbar();

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
        [$class, $method] = explode('@', $routeInfo[1]);
        $vars = $routeInfo[2];

        // On instancie le contrôleur et appele la méthode qui correspond grace au conteneur
        $controllerInstance = $container->get($class);
        if (method_exists($controllerInstance, $method)) {
            call_user_func_array([$controllerInstance, $method], $vars);
        } else {
            http_response_code(500);
            echo "Méthode '$method' introuvable dans le contrôleur '$class'.";
        }
        break;
}
