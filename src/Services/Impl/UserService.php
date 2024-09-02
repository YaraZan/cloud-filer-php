<?php

namespace App\Services\Impl;

require __DIR__ . "/../Config/config.php";

use App\Core\Session;
use App\Repositories\UserRepository;
use App\Services\Meta\UserServiceMeta;
use App\Utils\Tokenizer;
use App\Utils\Validator;
use Exception;

class UserService implements UserServiceMeta
{

    private UserRepository $repository;

    public function __construct() {
        $this->repository = new UserRepository();
    }

    protected function validateCredentials($credentials): void
    {
        if (!Validator::validateEmail($credentials['email'])) {
            throw new Exception('Invalid email format. Email should match pattern: example@mail.com', 400);
        }
        if (!Validator::validatePassword($credentials['password'])) {
            throw new Exception('Invalid password format. Password should be 12 digits 
    lenght, including numbers and spec. symbols: /, #, $, %, *, _, -', 400);
        }
    }

    public function getAllUsers(): array
    {
        return $this->repository->findAll();
    }

    public function getUser(int $id): array
    {
        return $this->repository->findOne($id);
    }

    public function register($credentials): void
    {
        Validator::validate([
            "email" => "required|max:50"
        ], $credentials);

        $this->validateCredentials($credentials);

        if ($credentials['password'] !== $credentials['confirm_password']) {
            throw new Exception('Passwords doesn`t match', 400);
        }

        $userExists = $this->repository->findOneWhere("email = " . "'" . $credentials['email'] . "'");
        if ((!empty($userExists))) {
            throw new Exception('User with provided email already exists', 400);
        }

        $this->repository->create([
            "name" => $credentials["name"],
            "email" => $credentials["email"],
            "password" => Validator::hashPassword($credentials["password"]),
        ]);
    }

    public function login($credentials): string 
    {
        $this->validateCredentials($credentials);

        $user = $this->repository->findOneWhere("email = " . "'" . $credentials['email'] . "'");
        if (empty($user)) {
            throw new Exception('User with provided email doesn`t exist', 400);
        }
        if (!Validator::verifyPasswords($credentials['password'], $user["password"])) {
            throw new Exception('Invalid password', 400);
        }

        // Create access token
        $accessToken = [
            "exp" => round(microtime(true) * 1000) + (ACCESS_TOKEN_EXP),
            "iat" => round(microtime(true)),
            "did" => hash('sha256', $_SERVER["HTTP_USER_AGENT"] . $_SERVER["REMOTE_ADDR"]),
            "uid" => $user["id"],
        ];
        $encodedAccessToken = Tokenizer::encode($accessToken);

        // Create refresh token
        $refreshToken = [
            "exp" => round(microtime(true) * 1000) + (REFRESH_TOKEN_EXP),
            "iat" => round(microtime(true)),
            "uid" => $user["id"],
        ];
        $encodedRefreshToken = Tokenizer::encode($refreshToken);

        $user = $this->repository->findOne($user["id"]);
        $this->repository->update($user["id"], ["refresh_token" => $encodedRefreshToken]);

        Session::create($encodedAccessToken);

        return $encodedAccessToken;
    }

    public function logout(): void
    {
        Session::destroy();
    }

    public function resetPassword($data): void
    {
        $authUser = Session::authorizedUser();
        
        $user = $this->repository->findOne($authUser["id"]);
        if (!Validator::verifyPasswords($data["old_password"], $user["password"])) {
            throw new Exception('Invalid password', 400);
        }
        if ($data["new_password"] !== $data["confirm_password"]) {
            throw new Exception('Passwords doesn`t match', 400);
        }

        $newPassword = Validator::hashPassword($data["new_password"]);

        $this->updateAuthorizedUser(["password" => $newPassword]);
    }

    public function updateAuthorizedUser($data): void
    {
        $authUser = Session::authorizedUser();
        if (!isset($authUser)) {
            throw new Exception('Not authorized', 401);
        }

        $this->repository->update((int) $authUser["id"], $data);
    }
}
