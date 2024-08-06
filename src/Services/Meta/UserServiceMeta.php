<?php

namespace App\Services\Meta;

interface UserServiceMeta {
    public function getAllUsers(): array;
    public function getUserById(int $id): object;
}
?>