<?php

namespace App\Services\Impl;

use App\Core\Request;
use App\Core\Session;
use App\Exceptions\AuthException;
use App\Exceptions\EmailException;
use App\Exceptions\PasswordException;
use App\Exceptions\SessionException;
use App\Exceptions\TokenException;
use App\Repositories\UserRepository;
use App\Repositories\UserRolesRepository;
use App\Services\Meta\AuthServiceMeta;
use App\Utils\Tokenizer;
use App\Utils\Validator;

class AuthService implements AuthServiceMeta
{
  private UserRepository $repository;
  private UserRolesRepository $userRolesRepository;

  public function __construct()
  {
    $this->repository = new UserRepository();
    $this->userRolesRepository = new UserRolesRepository();
  }

  public function register(array $credentials): void
  {
    $data = Validator::validate([
      "name" => ["required", "max:255"],
      "email" => ["required", "max:255", fn($email) => Validator::validateEmail($email)],
      "password" => ["required", "min:8", "max:20", fn($password) => Validator::validatePassword($password)],
    ], $credentials);

    if ($data['password'] !== $data['confirm_password']) {
      throw PasswordException::doesntMatch();
    }

    $userExists = $this->repository->findOneWhere("email = " . "'" . $data['email'] . "'");
    if ((!empty($userExists))) {
      throw EmailException::alreadyExists();
    }

    $this->repository->create([
      "name" => $data["name"],
      "email" => $data["email"],
      "password" => Validator::hashPassword($data["password"]),
    ]);
  }

  public function login(array $credentials)
  {
    Validator::validate([
      "email" => ["required", "max:255", fn($email) => Validator::validateEmail($email)],
      "password" => ["required", "min:8", "max:20", fn($password) => Validator::validatePassword($password)],
    ], $credentials);

    // Find matching user
    $user = $this->repository->findOneWhere("email = " . "'" . $credentials['email'] . "'");
    if (empty($user)) {
      throw EmailException::doesntExists();
    }
    if (!Validator::verifyPasswords($credentials['password'], $user["password"])) {
      throw PasswordException::incorrect();
    }

    // Add user roles
    $roles = $this->userRolesRepository->findAllWhere("user_id = " . $user["id"]);
    $user["roles"] = $roles;

    // Generate access and refresh tokens pair
    [$encodedAccessToken, $encodedRefreshToken] = Tokenizer::createTokenPair($user);

    // Update refresh token for authorized user
    $user = $this->repository->findOne($user["id"]);
    $this->repository->update($user["id"], ["refresh_token" => $encodedRefreshToken]);

    $this->setAccessToken($encodedAccessToken);
  }

  public function logout(array $user): void
  {
    // Set refresh_token to null
    $this->repository->update($user["id"], ["refresh_token" => null]);
  }

  public function resetPassword(array $user, array $data): void
  {
    if (!Validator::verifyPasswords($data["old_password"], $user["password"])) {
      throw PasswordException::incorrect();
    }
    if ($data["new_password"] !== $data["confirm_password"]) {
      throw PasswordException::doesntMatch();
    }

    $newPassword = Validator::hashPassword($data["new_password"]);

    $this->updateAuthenticatedUser($user, ["password" => $newPassword]);
  }

  public function updateAuthenticatedUser(array $user, array $data): void
  {
    $this->repository->update($user["id"], $data);
  }

  public function authenticate(array $token): void
  {
    $user = $token["user"];

    if ($token["exp"] <= round(microtime(true))) {
      $user = $this->repository->findOne($user["id"]);

      // Get refresh_token
      $refreshToken = Tokenizer::decode($user["refresh_token"]);

      // Check if refresh token is expired
      if ($refreshToken["exp"] <= round(microtime(true))) {
        $this->logout($user);

        throw AuthException::notAuthenticated();
      } else {
        // Add user roles
        $roles = $this->userRolesRepository->findAllWhere("user_id = " . $user["id"]);
        $user["roles"] = $roles;

        $newAccessToken = Tokenizer::createAccessToken($user);

        $this->setAccessToken($newAccessToken);
      }
    }
  }

  private function setAccessToken(string $accessToken): void
  {
    setcookie("token", $accessToken, [
      "expires" => time() + 86400,
      "path" => "/",
      "domain" => "",
      "secure" => true,
      "httponly" => true,
      "samesite" => "Lax"
    ]);
  }
}
