<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Services\Impl\UserService;

class UserController extends Controller
{
    public function register(Request $request): Response
    {
        $userService = App::getService('userService');
        
        $userService->register($request->getData());
    
        return new Response(["message" => "Registration successfull!"], 200);
    }

    public function login(Request $request): Response
    {
        $userService = App::getService('userService');

        $token = $userService->login($request->getData());

        setcookie("token", $token, [
            "expires" => time() + 86400,    // 1 hour from now
            "path" => "/",                 // Available in the entire domain
            "domain" => "",                // Default is the current domain
            "secure" => true,              // Ensures the cookie is sent over HTTPS only
            "httponly" => true,            // HTTP-only; inaccessible to JavaScript
            "samesite" => "Lax"            // Optional: helps prevent CSRF attacks
        ]);

        return new Response(["message" => "Authorization successfull!"], 200);
    }

    public function resetPassword(Request $request): Response
    {
        $userService = App::getService('userService');

        $userService->resetPassword($request->getData());

        return new Response(["message" => "Password reset successfully!"], 200);
    }
}