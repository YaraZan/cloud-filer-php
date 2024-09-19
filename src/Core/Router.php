<?php

namespace App\Core;

use App\Controllers\AdminController;
use App\Controllers\UserController;
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
            "POST /register" => new Route(UserController::class, "register"),
            "POST /login" => new Route(UserController::class, "login"),
            "POST /resetPassword" => new Route(UserController::class, "resetPassword", [AuthMiddleware::class]),
            "GET /admin/getAllUsers" => new Route(AdminController::class, "getAllUsers", [AuthMiddleware::class]),
            "GET /admin/getUser" => new Route(AdminController::class, "getUser", [AuthMiddleware::class]),
            "DELETE /admin/deleteUser" => new Route(AdminController::class, "deleteUser", [AuthMiddleware::class]),
            "PUT /admin/updateUser" => new Route(AdminController::class, "updateUser", [AuthMiddleware::class]),
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
        $route = parse_url((string) $request->getRoute(), PHP_URL_PATH);
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

    /**
     * Redirect to a specified URL
     *
     * @param string $url The URL to redirect to
     * @param int $statusCode HTTP status code for the redirect (default is 302)
     * @return void
     */
    public static function redirect(string $url, int $statusCode = 302): void
    {
        header("Location: " . BASE_URI . $url, true, $statusCode);
        exit(); // Make sure to stop script execution after redirect
    }
}