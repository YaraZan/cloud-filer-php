<?php

namespace App\Services\Meta;

interface UserServiceMeta
{
    public function getAllUsers(): array;
    public function getUser(int $id): array;
}
