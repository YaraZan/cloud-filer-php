<?php

namespace App\Services\Meta;

use App\Core\Request;

interface UserServiceMeta
{
    public function getAllUsers(): array;
    public function getUser(int $id): array;
    public function updateAuthorizedUser($data): void;
    public function login($credentials): string;
    public function register($credentials): void;
    public function logout(): void;
    public function resetPassword($data): void;
    public function resetToken(): string;
    public function authorize($token): void;
}
