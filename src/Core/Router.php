<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function registerRoutesarray($routes): void
    {
        $this->routes = $routes;
    }

    public function processRequest(Request $request)
    {
        $method = $request->getMethod();
        $route = $request->getRoute();
        $handler = $this->routes[$method][$route];

        [$controllerClass, $action] = $handler;
        $controller = new $controllerClass();

        return new $controller->$action($request);
    }
}
