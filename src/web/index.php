<?php

use Controllers\ArgumentController;
use Controllers\DebatController;
use Controllers\UserController;
use Symfony\Component\Dotenv\Dotenv;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

if (file_exists(__DIR__.'/../../vendor/autoload.php')) {
    require __DIR__.'/../../vendor/autoload.php';
} else {
    echo "<h1>Veuillez installer Composer</h1>";
    echo "<p>Pour générer le fichier <i>vendor/autoload.php</i>, executer la commande 
suivante :<pre>composer dump</pre> </a></p>";
    echo "<p>Merci pour votre visite !</p>";
    exit;
}

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../../.env');

session_start();


// Création du conteneur d'injection de dépendances
$container = require_once __DIR__ . '/../Container/container.php';

$dispatcher = simpleDispatcher(function (RouteCollector $r) {

    $r->get('/', function() {
        global $container;
        $debatController = $container->get(DebatController::class);
        $debatController->listDebats();
    });

    $r->get('/debats[/{page:\d+}]', function($args) {
        global $container;
        $debatController = $container->get(DebatController::class);
        $debatController->listDebats($args['page']);
    });

    $r->get('/debat/{id:\d+}', function($args) {
        global $container;
        $debatController = $container->get(DebatController::class);
        $debatController->viewDebat($args['id']);
    });

    $r->addRoute(["GET", "POST"], '/debate/{idDebate:\d}/arguments', function ($args) {
        global $container;
        $argumentController = $container->get(ArgumentController::class);
        $argumentController->list($args['idDebate']);
    });

    $r->get('/debat/creer', function () {
        if(isset($_SESSION['user'])) {
            global $container;
            $debatController = $container->get(DebatController::class);
            $debatController->createDebatForm();
        } else {
            header("Location: /login");
            exit;
        }
    });

    $r->post('/debat/creer', function () {
        if(isset($_SESSION['user'])) {
            global $container;
            $debatController = $container->get(DebatController::class);
            $debatController->createDebat();
        } else {
            header("Location: /login");
            exit;
        }
    });

    $r->get('/debate/{idDebate:\d}/poste', function ($args) {
        if(isset($_SESSION['user'])) {
            global $container;
            $argumentController = $container->get(ArgumentController::class);
            $argumentController->create($args['idDebate']);
        } else {
            header("Location: /login");
        }
    });

    $r->post('/debate/{idDebate:\d}/postArg', function ($args) {
        if(isset($_SESSION['user'])) {
            global $container;
            $argumentController = $container->get(ArgumentController::class);
            $argumentController->poste($args['idDebate']);
        } else {
            header("Location: /login");
        }
    });

    $r->post('/vote', function () {
        if(isset($_SESSION['user'])) {
            global $container;
            $argumentController = $container->get(ArgumentController::class);
            $argumentController->vote();
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
        }
    });

    $r->post('/unvote', function () {
        if(isset($_SESSION['user'])) {
            global $container;
            $argumentController = $container->get(ArgumentController::class);
            $argumentController->unvote();
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
        }
    });

    $r->addRoute(["GET", "POST"], '/login', function () {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? null;
            $mdp = $_POST['mdp'] ?? null;

            if ($email && $mdp) {
                global $container;
                $userController = $container->get(UserController::class);
                $userController->login($email, $mdp);
            } else {
                echo "Veuillez remplir tous les champs.";
            }
        } else {
            require __DIR__ . '/../app/Views/User/login.php';
        }
    });

    $r->get('/profile', function () {
        global $container;
        $userController = $container->get(UserController::class);
        $userController->showProfile();
    });

    $r->post('/updateProfile', function () {
        global $container;
        $userController = $container->get(UserController::class);
        $userController->updateProfile();
    });

    $r->get('/deleteProfile', function () {
        global $container;
        $userController = $container->get(UserController::class);
        $userController->deleteProfile();
    });

    $r->addRoute(["GET", "POST"], '/register', function () {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pseudo = $_POST['pseudo'] ?? null;
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;

            if ($pseudo && $email && $password) {
                UserController::createProfile($pseudo,$email, $password);
            } else {
                echo "Veuillez remplir tous les champs.";
            }
        } else {
            require __DIR__ . '/../app/Views/User/register.php'; // Affichage du formulaire
        }
    });

    $r->get('/logout', function () {
        UserController::logout();
        header("Location: /");
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
