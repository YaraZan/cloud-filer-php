<?php

namespace App\Services\Meta;

interface UserServiceMeta
{
    public function getAllUsers(): array;
    public function getUserById(int $id): object;
    public function updateAuthorizedUser($data): bool;
    public function login($credentials): bool;
    public function logout(): void;
    public function reset_password(string $old_password, string $new_password): bool;
}
