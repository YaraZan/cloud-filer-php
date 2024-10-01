<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Services\Impl\AdminService;

class AdminController extends Controller
{
  private AdminService $service;

  public function __construct()
  {
    $this->service = App::getService('adminService');
  }

  public function getAllUsers(): Response
  {
    $users = $this->service->getAllUsers();

    return new Response($users);
  }

  public function getUser(Request $request): Response
  {
    $data = $request->getData();

    $user = $this->service->getUser($data["id"]);

    return new Response($user);
  }

  public function updateUser(Request $request): Response
  {
    $data = $request->getData();

    $this->service->updateUser($data["id"], $data["update_data"]);

    return new Response(["message" => "User updated successfully"]);
  }

  public function deleteUser(Request $request): Response
  {
    $data = $request->getData();

    $this->service->deleteUser($data["id"]);

    return new Response(["message" => "User deleted successfully!"]);
  }
}
