<?php

namespace Tests;

use App\Factories\UserFactory;
use App\Repositories\UserRepository;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    // Test for UserFactory to create 15 user objects
    public function testUserFactoryGeneratesUsers(): void
    {
        $repository = new UserRepository();
        $factory = new UserFactory();

        $factory->work(15);

        $records = $repository->findAll();

        $this->assertCount(15, $records);

        $factory->done();
    }
}