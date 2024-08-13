<?php

namespace App\Services\Impl;

use App\Core\Response;
use App\Core\Session;
use App\Exceptions\EmailAlreadyExistsException;
use App\Exceptions\EmailDoesntExistException;
use App\Exceptions\InvalidEmailFormatException;
use App\Exceptions\InvalidPasswordFormatException;
use App\Exceptions\PasswordConfirmationException;
use App\Repositories\UserRepository;
use App\Services\Meta\UserServiceMeta;
use App\Utils\Validator;

class UserService implements UserServiceMeta
{

    private UserRepository $repository;

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

    public function getUser(int $id): object
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

        Session::create($user["id"]);
    }

    public function logout(): void
    {
        // impl logout logic
    }

    public function reset_password(string $old_password, string $new_password): void
    {
        // impl reset password logic
    }

    public function updateAuthorizedUser($data): void
    {
        $this->repository->update($data);
    }
}
