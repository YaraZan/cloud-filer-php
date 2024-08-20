<?php

namespace App\Services\Impl;

use App\Core\Response;
use App\Core\Session;
use App\Exceptions\EmailAlreadyExistsException;
use App\Exceptions\EmailDoesntExistException;
use App\Exceptions\InvalidEmailFormatException;
use App\Exceptions\InvalidPasswordException;
use App\Exceptions\InvalidPasswordFormatException;
use App\Exceptions\NotAuthorizedException;
use App\Exceptions\PasswordConfirmationException;
use App\Repositories\UserRepository;
use App\Services\Meta\UserServiceMeta;
use App\Utils\Validator;

class UserService implements UserServiceMeta
{

    private UserRepository $repository;

    public function __construct() {
        $this->repository = new UserRepository();
    }

    protected function validateCredentials($credentials): void
    {
        if (!Validator::validateEmail($credentials['email'])) {
            throw new InvalidEmailFormatException();
        }
        if (!Validator::validatePassword($credentials['password'])) {
            throw new InvalidPasswordFormatException();
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
        $this->validateCredentials($credentials);

        if ($credentials['password'] !== $credentials['confirm_password']) {
            throw new PasswordConfirmationException();
        }

        $userExists = $this->repository->findOneWhere("email = ", $credentials['email']);
        if (isset($userExists)) {
            throw new EmailAlreadyExistsException();
        }

        $this->repository->create([
            "name" => $credentials["name"],
            "email" => $credentials["email"],
            "password" => Validator::hashPassword($credentials["password"]),
        ]);

        $this->login($credentials);
    }

    public function login($credentials): void 
    {
        $this->validateCredentials($credentials);

        $user = $this->repository->findOneWhere("email = ", $credentials['email']);

        if (!isset($user)) {
            throw new EmailDoesntExistException();
        }
        if (!Validator::verifyPasswords($credentials['password'], $user["password"])) {
            throw new InvalidPasswordException();
        }

        Session::create($user);
    }

    public function logout(): void
    {
        Session::destroy();
    }

    public function resetPassword(string $old_password, string $new_password, string $confirm_password): void
    {
        $authUser = Session::authorizedUser();
        if (!isset($authUser)) {
            throw new NotAuthorizedException();
        }
        
        $user = $this->repository->findOne($authUser["id"]);
        if (!Validator::verifyPasswords($old_password, $user["password"])) {
            throw new InvalidPasswordException();
        }
        if ($new_password !== $confirm_password) {
            throw new PasswordConfirmationException();
        }

        $this->updateAuthorizedUser(["password" => $new_password]);
    }

    public function updateAuthorizedUser($data): void
    {
        $authUser = Session::authorizedUser();
        if (!isset($authUser)) {
            throw new NotAuthorizedException();
        }

        $this->repository->update((int) $authUser["id"], $data);
    }
}
