<?php

namespace App\Core;

/**
 * An endpoint of API 
 * Also a mapping of controller action and middleware
 */
class Route 
{
    /** Route controller */
    private $controller;

    /** Route controller's method */
    private string $method;

    /** Route middleware */
    private array $middlewares;

    public function __construct($controller, string $method, ?array $middlewares = []) {
        $this->controller = new $controller();
        $this->method = $method;
        $this->middlewares = $middlewares;
    }

    /** 
     * Navigate to route
     * 
     * @param Request $request An incoming request
     * @return Response Action response
     */
    public function navigate(Request $request): Response
    {
        $action = $this->method;

        if (isset($this->middleware)) {
            foreach ($this->middlewares as $middleware) {
                if ($middleware instanceof Middleware) {
                    $middlewareInstance = new $middleware();

                    $middlewareInstance->handle($request);
                }
            }
        }

        return $this->controller->$action($request);
    }
}