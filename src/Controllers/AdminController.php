<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Services\Impl\AdminService;

class AdminController extends Controller 
{
    private AdminService $adminService;

    public function __construct() {
        $this->adminService = App::getService("adminService");
    }

    public function getAllUsers(): Response {
        $users = $this->adminService->getAllUsers();

        return new Response($users);
    }

    public function getUser(Request $request): Response {
        $data = $request->getData();

        $user = $this->adminService->getUser($data["id"]);

        return new Response($user);
    }

    public function deleteUser(Request $request): Response {
        $data = $request->getData();

        $this->adminService->deleteUser($data["id"]);

        return new Response(["message" => "User deleted successfully!"]);
    }

    public function updateUser(Request $request): Response {
        $data = $request->getData();

        $this->adminService->updateUser($data["id"], $data);

        return new Response(["message" => "User updated successfully!"]);
    }
}