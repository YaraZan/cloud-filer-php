<?php

namespace App\Services\Meta;

interface UserServiceMeta
{
    public function getAllUsers(): array;
    public function getUser(int $id): object;
    public function updateAuthorizedUser($data): void;
    public function login($credentials): void;
    public function register($credentials): void;
    public function logout(): void;
    public function reset_password(string $old_password, string $new_password): void;
}
