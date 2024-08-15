<?php

namespace App\Core;

use App\Controllers\AuthController;

class Router
{
    private array $routes = [];

    public function __construct() {
        $this->registerRoutesarray([
            "GET /login" => [AuthController::class, "view"],
        ]);
    }

    public function registerRoutesarray($routes): void
    {
        $this->routes = $routes;
    }

    public function processRequest(Request $request)
    {
        $method = $request->getMethod();
        $route = $request->getRoute();
        $routeKey = $method . ' ' . $route;

        if (isset($this->routes[$routeKey])) {
            $handler = $this->routes[$routeKey];

            [$controllerClass, $action] = $handler;
            $controller = new $controllerClass();
    
            $response = $controller->$action($request);
            
            if ($response instanceof Response) {
                $response->send();
            }
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}
