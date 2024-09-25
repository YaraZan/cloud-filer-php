<?php

namespace Tests;

require_once __DIR__ . '/../src/Exceptions/UserExceptions.php';
require_once __DIR__ . '/../src/Exceptions/AuthExceptions.php';
require_once __DIR__ . '/../src/Exceptions/ValidatorExceptions.php';

use App\Exceptions\EmailException;
use App\Exceptions\PasswordException;
use App\Exceptions\ValidatorException;
use App\Repositories\UserRepository;
use App\Services\Impl\AuthService;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
  public function testUserCanRegisterWithValidPassword(): void
  {
    $repository = new UserRepository();
    $authService = new AuthService();
    $validUser = [
        "name" => "John Doe",
        "email" => "johndoe@example.com",
        "password" => "ValidPass123#%",
        "confirm_password" => "ValidPass123#%"
    ];

    $authService->register($validUser);
    $this->assertTrue(true);

    $repository->clearTable();
  }

  public function testUserCannotRegisterWithInvalidPassword(): void
  {
    $this->expectException(PasswordException::class);

    $repository = new UserRepository();
    $authService = new AuthService();
    $invalidPasswordUser = [
        "name" => "John Doe",
        "email" => "johndoe@example.com",
        "password" => "short",
        "confirm_password" => "short"
    ];

    $authService->register($invalidPasswordUser);

    $repository->clearTable();
  }

  public function testUserCannotRegisterWithInvalidEmail(): void
  {
      $this->expectException(EmailException::class);

      $repository = new UserRepository();
      $authService = new AuthService();
      $invalidEmailUser = [
          "name" => "John Doe",
          "email" => "invalid-email",
          "password" => "ValidPass123",
          "confirm_password" => "ValidPass123"
      ];

      $authService->register($invalidEmailUser);

    $repository->clearTable();
  }

  public function testUserCannotRegisterIfUserWithProvidedEmailAlreadyExists(): void
  {
      $this->expectException(EmailException::class);

      $repository = new UserRepository();
      $authService = new AuthService();
      $existingUser = [
          "name" => "John Doe",
          "email" => "existinguser@example.com",
          "password" => "ValidPass123#%",
          "confirm_password" => "ValidPass123#%"
      ];

      // Register user for the first time
      $authService->register($existingUser);

      // Try to register it for the second
      $authService->register($existingUser);

    $repository->clearTable();
  }

  public function testUserCannotEnterNameWithMoreThan255Digits(): void
  {
    $this->expectException(ValidatorException::class);

    $repository = new UserRepository();
    $authService = new AuthService();
    $existingUser = [
        "name" => str_repeat('John Doe', 255),
        "email" => "existinguser@example.com",
        "password" => "ValidPass123#%",
        "confirm_password" => "ValidPass123#%"
    ];

    // Register user for the first time
    $authService->register($existingUser);

    $repository->clearTable();
  }
}
