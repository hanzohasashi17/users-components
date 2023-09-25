<?php

require '../vendor/autoload.php';

use Aura\SqlQuery\QueryFactory;
use League\Plates\Engine;
use Symfony\Component\HttpFoundation\Session\Session;

$builder = new DI\ContainerBuilder();
$builder->addDefinitions([
    Engine::class => function () {
        return new Engine('../app/views');
    },
    QueryFactory::class => function () {
        return new QueryFactory('mysql');
    },
    PDO::class => function () {
        return new PDO("mysql:host=localhost;dbname=components", "root", "");
    }
]);
$container = $builder->build();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\controllers\user\UserController', 'index']);
    $r->addRoute('GET', '/register', ['App\controllers\auth\RegistrationController', 'showRegisterPage']);
    $r->addRoute('POST', '/register', ['App\controllers\auth\RegistrationController', 'register']);
    $r->addRoute('GET', '/login', ['App\controllers\auth\LoginController', 'showLoginPage']);
    $r->addRoute('POST', '/login', ['App\controllers\auth\LoginController', 'login']);
    $r->addRoute('GET', '/logout', ['App\controllers\auth\LogoutController', 'logout']);
    $r->addRoute('GET', '/userCreate', ['App\controllers\user\UserController', 'showUserCreatePage']);
    $r->addRoute('POST', '/userCreate', ['App\controllers\user\UserController', 'userCreate']);
    $r->addRoute('GET', '/userEdit/{id:\d+}', ['App\controllers\user\UserController', 'showUserEditPage']);
    $r->addRoute('POST', '/userEdit', ['App\controllers\user\UserController', 'userEdit']);
});

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
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        $container->call($handler, $vars);
        break;
}