<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;

class AuthController extends Controller
{
    public function register(Request $request): Response
    {
        $service = App::getService('authService');

        $service->register($request->getData());

        return new Response(["message" => "Registration successfull!"], 200);
    }

    public function login(Request $request): Response
    {
        $service = App::getService('authService');

        $service->login($request->getData());

        return new Response(["message" => "Authorization successfull!"], 200);
    }

    public function resetPassword(Request $request): Response
    {
        $service = App::getService('authService');

        $service->resetPassword($request->getData());

        return new Response(["message" => "Password reset successfully!"], 200);
    }
}
