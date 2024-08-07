<?php

namespace App\Services\Meta;

interface AdminServiceMeta {
    public function getAllUsers(): array;
    public function getUser(int $id): object;
    public function deleteUser(int $id): void;
    public function updateUser(int $id, $data): void;
}
?>
