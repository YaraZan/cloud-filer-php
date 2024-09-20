<?php

namespace App\Services\Impl;

require_once __DIR__ . "/../../Config/config.php";

use App\Core\Router;
use App\Core\Session;
use App\Exceptions\EmailException;
use App\Exceptions\PasswordException;
use App\Exceptions\TokenException;
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
            throw EmailException::invalidFormat();
        }
        if (!Validator::validatePassword($credentials['password'])) {
            throw PasswordException::invalidFormat();
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
            throw PasswordException::doesntMatch();
        }

        $userExists = $this->repository->findOneWhere("email = " . "'" . $credentials['email'] . "'");
        if ((!empty($userExists))) {
            throw EmailException::alreadyExists();
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
            throw EmailException::doesntExists();
        }
        if (!Validator::verifyPasswords($credentials['password'], $user["password"])) {
            throw PasswordException::incorrect();
        }

        $roles = $this->userRolesRepository->findWhere("user_id = " . $user["id"]);
        $user["roles"] = $roles;

        $encodedAccessToken = Tokenizer::createAccessToken($user);

        $encodedRefreshToken = Tokenizer::createRefreshToken($user);

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
        $authUser = Session::user();

        $user = $this->repository->findOne($authUser["id"]);
        if (!Validator::verifyPasswords($data["old_password"], $user["password"])) {
            throw PasswordException::incorrect();
        }
        if ($data["new_password"] !== $data["confirm_password"]) {
            throw PasswordException::doesntMatch();
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
            throw TokenException::doesntExist();
        }
        // Check if sent token equal to stored token 
        if ($storedToken !== $clientToken) {
            throw TokenException::invalid();
        }

        $accessToken = Tokenizer::decode($clientToken);
        
        // Check if access token is expired
        if ($accessToken["exp"] <= round(microtime(true))) {
            $this->resetToken();
        }
    }
}
