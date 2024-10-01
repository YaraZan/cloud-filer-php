<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Services\Impl\AuthService;

class AuthController extends Controller
{
  private AuthService $service;

  public function __construct() {
    $this->service = App::getService('authService');
  }

  public function register(Request $request): Response
  {
    $this->service->register($request->getData());

    return new Response(["message" => "Registration successfull!"], 200);
  }

  public function login(Request $request): Response
  {
    $this->service->login($request->getData());

    return new Response(["message" => "Authenticated successfully!"], 200);
  }

  public function resetPassword(Request $request): Response
  {
    $data = $request->getData();
    $user = $request->getUser();

    $this->service->resetPassword($user, $data);

    return new Response(["message" => "Password reset successfully!"], 200);
  }

  public function updateAuthenticatedUser(Request $request): Response
  {
    $data = $request->getData();
    $user = $request->getUser();

    $this->service->updateAuthenticatedUser($user, $data);

    return new Response(["message" => "User updated successfully!"], 200);
  }
}
