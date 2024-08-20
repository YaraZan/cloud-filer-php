<?php

namespace App\Core;

class Route 
{
    private $controller;
    private string $method;
    private $middleware;

    public function __construct($controller, string $method, $middleware = null) {
        $this->controller = new $controller();
        $this->method = $method;
        $this->middleware = $middleware;
    }

    public function navigate(Request $request)
    {
        $action = $this->method;

        if (isset($this->middleware)) {
            $this->middleware->handle($request);
        }

        $this->controller->$action($request);
    }
}