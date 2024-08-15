<?php

use App\Controllers\AuthController;
use App\Core\Request;
use App\Core\Response;

require_once 'vendor/autoload.php';

$routes = [
    "GET /login" => [AuthController::class, "view"]
];

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$data = $method === 'POST' ? $_POST : $_GET;

$request = new Request($data, $uri, $method);

$routeKey = $method . ' ' . strtok($uri, '?');
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
