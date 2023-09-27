<?php
session_start();
require '../vendor/autoload.php';

use Aura\SqlQuery\QueryFactory;
use League\Plates\Engine;

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
    $r->addRoute('GET', '/createUser', ['App\controllers\user\UserController', 'showCreateUserPage']);
    $r->addRoute('POST', '/createUser', ['App\controllers\user\UserController', 'createUser']);
    $r->addRoute('GET', '/editUserProfile/{id:\d+}', ['App\controllers\user\UserController', 'showEditUserProfilePage']);
    $r->addRoute('POST', '/editUserProfile', ['App\controllers\user\UserController', 'editUserProfile']);
    $r->addRoute('GET', '/editUserSecurity/{id:\d+}', ['App\controllers\user\UserController', 'showEditUserSecurityPage']);
    $r->addRoute('POST', '/editUserSecurity', ['App\controllers\user\UserController', 'editUserSecurity']);
    $r->addRoute('GET', '/editUserStatus/{id:\d+}', ['App\controllers\user\UserController', 'showEditUserStatusPage']);
    $r->addRoute('POST', '/editUserStatus', ['App\controllers\user\UserController', 'editUserStatus']);
    $r->addRoute('GET', '/editUserMedia/{id:\d+}', ['App\controllers\user\UserController', 'showEditUserMediaPage']);
    $r->addRoute('POST', '/editUserMedia', ['App\controllers\user\UserController', 'editUserMedia']);
    $r->addRoute('GET', '/deleteUser/{id:\d+}', ['App\controllers\user\UserController', 'deleteUser']);
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