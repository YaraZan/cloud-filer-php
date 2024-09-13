<?php

namespace App\Services\Impl;

use App\Repositories\UserRepository;
use App\Repositories\UserRolesRepository;
use App\Services\Meta\AdminServiceMeta;

class AdminService implements AdminServiceMeta
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->findAll();
    }

    public function getUser(int $id): array
    {
        return $this->userRepository->findOne($id);
    }

    public function deleteUser(int $id): void
    {
        $this->userRepository->delete($id);
    }

    public function updateUser(int $id, $data): void
    {
        $this->userRepository->update($id, $data);   
    }
}