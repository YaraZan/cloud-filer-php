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
    private $middleware;

    public function __construct($controller, string $method, $middleware = null) {
        $this->controller = new $controller();
        $this->method = $method;
        $this->middleware = new $middleware();
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
            $this->middleware->handle($request);
        }

        return $this->controller->$action($request);
    }
}