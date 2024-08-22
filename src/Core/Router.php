<?php

namespace App\Core;

use App\Controllers\AuthController;
use App\Middleware\AuthMiddleware;
use Exception;

/**
 * Used for endpoint registration
 * Processes incoming requests to actions
 */
class Router
{
    /** Defined routes */
    private array $routes = [];

    /** Initialization of app endpoints as routes */
    public function __construct() {
        $this->registerRoutesarray([
            "POST /register" => new Route(AuthController::class, "register"),
            "POST /login" => new Route(AuthController::class, "login"),
            "POST /resetPassword" => new Route(AuthController::class, "resetPassword", AuthMiddleware::class),
        ]);
    }

    /**
     * Registrating routes
     * 
     * @param array $routes Routes
     * @return void
     */
    public function registerRoutesarray(array $routes): void
    {
        $this->routes = $routes;
    }

    /**
     * Process incoming request
     * If request is successfull, returns some payload
     * If not, returns error object.
     * 
     * @param Request $request Incoming request
     * @return void
     */
    public function processRequest(Request $request): void
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