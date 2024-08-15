<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Request;
use App\Core\Response;
use App\Services\Impl\UserService;

class AuthController
{
    private UserService $userService;

    public function __construct() {
        $this->userService = App::getService('userService');
    }

    public function view(): Response
    {
        return new Response("login.php", [], 200);
    }
}