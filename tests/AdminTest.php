<?php

namespace Tests;

require_once __DIR__ . '/../src/Services/Impl/AdminSevrice.php';

use App\Factories\UserFactory;
use App\Repositories\UserRepository;
use App\Services\Impl\AdminService;
use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase
{
  private AdminService $adminService;
  private UserRepository $userRepository;
  private UserFactory $userFactory;

  protected function setUp(): void
  {
    parent::setUp();
    $this->adminService = new AdminService();
    $this->userFactory = new UserFactory();
    $this->userRepository = new UserRepository;
  }

  protected function tearDown(): void
  {
    parent::tearDown();

    $this->userRepository->clearTable();
  }

  public function testAdminCanGetAllUsers()
  {
    $this->userFactory->work(15);

    $users = $this->adminService->getAllUsers();

    $this->assertCount(15, $users);
  }

  public function testAdminCanGetOneUser()
  {
    $user = $this->userFactory->work(1);

    $searchedUser = $this->adminService->getUser($user[0]["id"]);

    $this->assertNotEmpty($searchedUser);
  }

  public function testAdminCanDeleteUser()
  {
    $user = $this->userFactory->work(1);

    $this->adminService->deleteUser($user[0]["id"]);

    $users = $this->adminService->getAllUsers();

    $this->assertEmpty($users);
  }

  public function testAdminCanUpdateUser()
  {
    $user = $this->userFactory->work(1);

    $user = $user[0];

    $this->adminService->updateUser(
      $user["id"],
      ["name" => "Johnatan Dorian"]
    );

    $searchedUser = $this->adminService->getUser($user["id"]);

    $this->assertEquals($searchedUser["name"], "Johnatan Dorian");
    $this->assertNotEquals($user["name"], $searchedUser["name"]);
  }
}
