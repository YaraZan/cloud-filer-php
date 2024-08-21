<?php

namespace App\Core;

use App\Controllers\AuthController;
use Exception;

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

        try {
            $routeClass = $this->routes[$routeKey];
            $response = $routeClass->navigate($request);

            if (!isset($this->routes[$routeKey])) {
                throw new Exception("Not found", 404);
            }

            if ($response instanceof Response) {
                $response->send();
            }
        } catch (\Exception $e) {
            $errorResponse = new Response(["error" => ["message" => $e->getMessage()]], $e->getCode());

            $errorResponse->send();
        }


    }
}