<?php

namespace App\Services\Impl;

require_once __DIR__ . "/../../Config/config.php";

use App\Repositories\UserRepository;
use App\Services\Meta\UserServiceMeta;

class UserService implements UserServiceMeta
{

    private UserRepository $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function getAllUsers(): array
    {
        return $this->repository->findAll();
    }

    public function getUser(int $id): array
    {
        return $this->repository->findOne($id);
    }
}
