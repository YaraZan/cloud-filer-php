<?php

namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Request;
use App\Core\Session;
use App\Services\Impl\UserService;
use App\Utils\Tokenizer;
use Exception;

class AuthMiddleware extends Middleware
{
    private UserService $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    public function handle(Request $request): void
    {
        // Get request headers
        $headers = $request->getHeaders();

        // Check if there is no authorization schema
        if (!isset($headers["Authorization"])) {
            throw new Exception("No authorization schema provided!", 401);
        }

        // Desctructurize schema and token
        [$schema, $token] = explode(" ", $headers["Authorization"]); 

        // Check if used schema is valid
        if ($schema !== "Bearer") {
            throw new Exception("Invalid authorization schema", 401);
        }
        // Check if token provided
        if (!isset($token)) {
            throw new Exception("Token invalid", 401);
        }

        $this->userService->authorize($token);
    }
}