<?php

use App\Controllers\AuthController;
use App\Core\App;
use App\Core\Request;
use App\Core\Response;

require_once 'vendor/autoload.php';
require_once 'src/Config/config.php';

App::registerServices();

$routes = [
    "GET /login" => [AuthController::class, "view"]
];

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$data = $method === 'POST' ? $_POST : $_GET;

$request = new Request($data, $uri, $method);

$routeKey = $_SERVER['REQUEST_METHOD'] . ' ' . str_replace(BASE_URI, '', $_SERVER['REQUEST_URI']);
if (isset($routes[$routeKey])) {
    [$controller, $method] = $routes[$routeKey];
    $controllerInstance = new $controller();
    $response = $controllerInstance->$method($request);

    if ($response instanceof Response) {
        $response->send();
    }
} else {
    http_response_code(404);
    echo "404 Not Found";
}
