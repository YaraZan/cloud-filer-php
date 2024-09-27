<?php

namespace Tests;

require_once __DIR__ . '/../src/Exceptions/AuthExceptions.php';
require_once __DIR__ . '/../src/Exceptions/ValidatorExceptions.php';

use App\Exceptions\AuthException;
use App\Exceptions\ValidatorException;
use App\Repositories\UserRepository;
use App\Services\Impl\AuthService;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
  private UserRepository $userRepository;
  private AuthService $authService;

  protected function setUp(): void
  {
      parent::setUp();
      $this->userRepository = new UserRepository();
      $this->authService = new AuthService();
  }

  protected function tearDown(): void
  {
      parent::tearDown();

      $this->userRepository->clearTable();
  }

  public function testUserCannotRegisterWithInvalidEmail(): void
  {
    $this->expectException(AuthException::class);

    $invalidEmailUser = [
        "name" => "John Doe",
        "email" => "invalid-email",
        "password" => "ValidPass123",
        "confirm_password" => "ValidPass123"
    ];

    $this->authService->register($invalidEmailUser);
  }

  public function testUserCannotRegisterWithNameMoreThan255Digits(): void
  {
    $this->expectException(ValidatorException::class);

    $existingUser = [
        "name" => "Very l" . str_repeat("o", 255) . "ng name",
        "email" => "johndoe@example.com",
        "password" => "ValidPass123#%",
        "confirm_password" => "ValidPass123#%"
    ];

    $this->authService->register($existingUser);
  }

  public function testUserCannotRegisterWithEmailMoreThan255Digits(): void
  {
    $this->expectException(ValidatorException::class);

    $existingUser = [
        "name" => "John Doe",
        "email" => "Very l" . str_repeat("o", 255) . "ng email",
        "password" => "ValidPass123#%",
        "confirm_password" => "ValidPass123#%"
    ];

    $this->authService->register($existingUser);
  }

  public function testUserCannotRegisterWithPasswordMoreThan20Digits(): void
  {
    $this->expectException(ValidatorException::class);

    $existingUser = [
        "name" => "John Doe",
        "email" => "johndoe@example.com",
        "password" => "Very l" . str_repeat("o", 20) . "ng password#%",
        "confirm_password" => "ValidPass123#%"
    ];

    $this->authService->register($existingUser);
  }

  public function testUserCannotRegisterWithPasswordLessThan8Digits(): void
  {
    $this->expectException(ValidatorException::class);

    $existingUser = [
        "name" => "John Doe",
        "email" => "johndoe@example.com",
        "password" => "Sm4ll#%",
        "confirm_password" => "Sm4ll#%"
    ];

    $this->authService->register($existingUser);
  }

  public function testUserCannotRegisterIfPasswordsDoesntMatch(): void
  {
    $this->expectException(AuthException::class);

    $existingUser = [
        "name" => "John Doe",
        "email" => "johndoe@example.com",
        "password" => "ValidPass123#%",
        "confirm_password" => "ValidPass12#%"
    ];

    $this->authService->register($existingUser);
  }

  public function testUserCannotRegisterIfUserWithProvidedEmailAlreadyExists(): void
  {
    $this->expectException(AuthException::class);

    $existingUser = [
        "name" => "John Doe",
        "email" => "johndoe@example.com",
        "password" => "ValidPass123#%",
        "confirm_password" => "ValidPass123#%"
    ];

    // Register user for the first time
    $this->authService->register($existingUser);

    // Try to register it for the second
    $this->authService->register($existingUser);
  }

  public function testUserCanRegisterWithValidPassword(): void
  {
    $validUser = [
        "name" => "John Doe",
        "email" => "johndoe@example.com",
        "password" => "ValidPass123#%",
        "confirm_password" => "ValidPass123#%"
    ];

    $this->authService->register($validUser);

    $user = $this->userRepository->findOneWhere(sprintf("email = '%s'", $validUser["email"]));

    $this->assertNotEmpty($user);
  }

  public function testUserCannotLoginWithNotExistingEmail(): void
  {
    $this->expectException(AuthException::class);

    $validUser = [
        "name" => "John Doe",
        "email" => "johndoe@example.com",
        "password" => "ValidPass123#%",
        "confirm_password" => "ValidPass123#%"
    ];

    $this->authService->register($validUser);

    $validUser["email"] = "dummy_email@example.com";

    $this->authService->login($validUser);
  }

  public function testUserCannotLoginWithWrongPassword(): void
  {
    $this->expectException(AuthException::class);

    $validUser = [
        "name" => "John Doe",
        "email" => "johndoe@example.com",
        "password" => "ValidPass123#%",
        "confirm_password" => "ValidPass123#%"
    ];

    $this->authService->register($validUser);

    $validUser["password"] = "ValidPass1234#%";

    $this->authService->login($validUser);
  }

  public function testUserCanLoginWithCorrectData(): void
  {
    $validUser = [
        "name" => "John Doe",
        "email" => "johndoe@example.com",
        "password" => "ValidPass123#%",
        "confirm_password" => "ValidPass123#%"
    ];

    $this->authService->register($validUser);

    $this->authService->login($validUser);

    $user = $this->userRepository->findOneWhere(sprintf("email = '%s'", $validUser["email"]));

    $this->assertNotNull($user["refresh_token"]);
  }
}
