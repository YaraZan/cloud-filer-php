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
}