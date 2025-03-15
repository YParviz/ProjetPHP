<?php

use Symfony\Component\Dotenv\Dotenv;

if(file_exists(__DIR__.'/../../vendor/autoload.php')){
    require __DIR__.'/../../vendor/autoload.php';
} else {
    echo "<h1>Veuillez installer Composer</h1>";
    echo "<p>Pour générer le fichier <i>vendor/autoload.php</i>, executer la commande 
suivante :<pre>composer dump</pre> </a></p>";
    echo "<p>Merci pour votre visite !</p>";
    exit;
}


$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../../.env');


$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {

    $r->get('/', function() {
        echo 'Page d\'accueil';
    });

    $r->addRoute(["GET", "POST"], '/argument/{id:\d}', function ($args) {
        $amodel = new \Models\ArgumentModel();
        $argument = $amodel->getById($args['id']);
        $acontroller = new \Controllers\ArgumentController;
        $acontroller->print($argument);
    });
});



////-----------------------

// A NE PAS MODIFIER !!!

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);



$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

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