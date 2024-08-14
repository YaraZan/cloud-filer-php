<?php

use App\Core\Response;

require_once 'vendor/autoload.php';

$routes = [
    // routes
];

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$routeKey = $method . ' ' . strtok($uri, '?');
if (isset($routes[$routeKey])) {
    [$controller, $method] = $routes[$routeKey];
    $controllerInstance = new $controller();
    $response = $controllerInstance->$method($_REQUEST);

    if ($response instanceof Response) {
        $response->send();
    }
} else {
    http_response_code(404);
    echo "404 Not Found";
}
