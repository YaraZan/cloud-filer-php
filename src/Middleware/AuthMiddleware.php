<?php

namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Request;
use App\Core\Session;
use App\Exceptions\TokenExpiredException;
use App\Exceptions\TokenInvalidException;
use App\Utils\Tokenizer;

class AuthMiddleware extends Middleware
{
    public function handle(Request $request): void
    {
        $data = $request->getData();

        $storedToken = Session::get("token");
        $receivedToken = $data["token"] ?? null;

        if (!isset($receivedToken)) {
            throw new TokenInvalidException();
        }

        if ($storedToken !== $receivedToken) {
            throw new TokenInvalidException();
        }

        $decodedToken = Tokenizer::decode($storedToken);

        if ($decodedToken->exp < time()) {
            throw new TokenExpiredException();
        }
    }
}