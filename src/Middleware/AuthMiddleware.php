<?php

namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Request;
use App\Core\Session;
use App\Exceptions\TokenException;
use App\Services\Impl\AuthService;
use App\Services\Impl\UserService;
use App\Utils\Tokenizer;
use Exception;

class AuthMiddleware extends Middleware
{
    private AuthService $authService;

    public function __construct() {
        $this->authService = new AuthService();
    }

    public function handle(Request $request): void
    {
        // Get request headers
        $token = $request->getToken();

        if (!isset($token)) {
          throw TokenException::doesntExist();
        }

        $this->authService->authenticate($token);
    }
}
