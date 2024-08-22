<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Services\Impl\UserService;

class AuthController extends Controller
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

        return new Response(["message" => "Authorization successfull!", "token" => $token], 200);
    }

    public function resetPassword(Request $request): Response
    {
        $userService = App::getService('userService');

        $userService->resetPassword($request->getData());

        return new Response(["message" => "Password reset successfully!"], 200);
    }
}