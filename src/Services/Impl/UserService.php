<?php

namespace App\Services\Impl;

require_once __DIR__ . "/../../Config/config.php";

use App\Core\DB;
use App\Core\Request;
use App\Core\Router;
use App\Core\Session;
use App\Repositories\UserRepository;
use App\Repositories\UserRolesRepository;
use App\Services\Meta\UserServiceMeta;
use App\Utils\Tokenizer;
use App\Utils\Validator;
use Exception;

class UserService implements UserServiceMeta
{

    private UserRepository $repository;
    private UserRolesRepository $userRolesRepository;

    public function __construct()
    {
        $this->repository = new UserRepository();
        $this->userRolesRepository = new UserRolesRepository();
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
        // Validate user credentials
        $this->validateCredentials($credentials);

        $user = $this->repository->findOneWhere("email = " . "'" . $credentials['email'] . "'");
        if (empty($user)) {
            throw new Exception('User with provided email doesn`t exist', 400);
        }
        if (!Validator::verifyPasswords($credentials['password'], $user["password"])) {
            throw new Exception('Invalid password', 400);
        }

        // Fetch user roles
        $roles = $this->userRolesRepository->findWhere("user_id = " . $user["id"]);
        $user["roles"] = $roles;

        // Create access token
        $encodedAccessToken = Tokenizer::createAccessToken($user);

        // Create refresh token
        $encodedRefreshToken = Tokenizer::createRefreshToken($user);

        // Save new refresh token
        $user = $this->repository->findOne($user["id"]);
        $this->repository->update($user["id"], ["refresh_token" => $encodedRefreshToken]);

        // Start new session with created access token
        Session::create($encodedAccessToken);

        return $encodedAccessToken;
    }

    public function logout(): void
    {
        Session::destroy();
    }

    public function resetPassword($data): void
    {
        $authUser = Session::user();

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
        $authUser = Session::user();

        $this->repository->update((int) $authUser["id"], $data);
    }

    public function resetToken(): string
    {
        $authUser = Session::user();

        $user = $this->repository->findOne($authUser["id"]);

        $refreshToken = Tokenizer::decode($user["refresh_token"]);

        // If refresh token is expired, logout user
        if ($refreshToken["exp"] <= round(microtime(true))) {
            Router::redirect('/logout');
        } else {
            $accessToken = Tokenizer::createAccessToken($authUser);

            return $accessToken;
        }
    }

    public function authorize($clientToken): void 
    {   
        $storedToken = Session::get("token");

        // Check if session token exists
        if (!isset($storedToken)) {
            throw new Exception("Token doesn't exist!", 401);
        }
        // Check if sent token equal to stored token 
        if ($storedToken !== $clientToken) {
            throw new Exception("Token invalid", 401);
        }

        $accessToken = Tokenizer::decode($clientToken);
        
        // Check if access token is expired
        if ($accessToken["exp"] <= round(microtime(true))) {
            $this->resetToken();
        }
    }
}
