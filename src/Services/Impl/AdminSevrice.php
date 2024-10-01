<?php

namespace App\Services\Impl;

use App\Repositories\UserRepository;
use App\Services\Meta\AdminServiceMeta;
use App\Services\Impl\AuthService;

class AdminService implements AdminServiceMeta
{
  private UserRepository $userRepository;
  private AuthService $authService;

  public function __construct()
  {
    $this->userRepository = new UserRepository();
    $this->authService = new AuthService();
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
    $user = $this->userRepository->findOne($id);

    $this->authService->logout($user);

    $this->userRepository->delete($id);
  }

  public function updateUser(int $id, $data): void
  {
    $user = $this->userRepository->findOne($id);

    $this->authService->logout($user);

    $this->userRepository->update($id, $data);
  }
}
