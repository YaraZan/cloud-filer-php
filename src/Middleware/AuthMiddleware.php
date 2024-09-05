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
        $cookies = $request->getCookies();

        // Check if there is no authorization schema
        if (!isset($cookies['token'])) {
            throw new Exception("No token provided!", 401);
        }

        $token = $cookies['token'];

        $this->userService->authorize($token);
    }
}