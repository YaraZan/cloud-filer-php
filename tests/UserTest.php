<?php

namespace Tests;

use App\Factories\UserFactory;
use App\Repositories\UserRepository;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
  private UserRepository $userRepository;
  private UserFactory $userFactory;

  protected function setUp(): void
  {
      parent::setUp();
      $this->userRepository = new UserRepository();
      $this->userFactory = new UserFactory();
  }

  protected function tearDown(): void
  {
      parent::tearDown();

      $this->userRepository->clearTable();
  }

  public function testUserFactoryGeneratesAndDeletesUsers(): void
  {
    $this->userFactory->work(15);

    $records = $this->userRepository->findAll();

    $this->assertCount(15, $records);

    $this->userFactory->done();

    $deletedRecords = $this->userRepository->findAll();

    $this->assertCount(0, $deletedRecords);
  }
}
