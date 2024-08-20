<?php

namespace App\Core;

class Route 
{
    private Controller $controller;
    private string $method;
    private ?Middleware $middleware;

    public function __construct($controller, string $method, $middleware = null) {
        $this->controller = $controller;
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