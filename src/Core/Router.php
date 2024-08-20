<?php

namespace App\Core;

use App\Controllers\AuthController;

class Router
{
    private array $routes = [];

    public function __construct() {
        $this->registerRoutesarray([
            "POST /register" => new Route(AuthController::class, "register"),
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

        if (!isset($this->routes[$routeKey])) {
            http_response_code(404);
            
            echo "404 Not Found";
        }

        $routeClass = $this->routes[$routeKey];

        $response = $routeClass->navigate($request);
        
        if ($response instanceof Response) {
            $response->send();
        }
    }
}