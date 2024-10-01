<?php

namespace App\Factories;

use App\Core\Factory;

class UserFactory extends Factory
{
  public function work(int $numRecords): array
  {
    return $this->seed($numRecords, [
      "name" => fn() => $this->faker->name(),
      "email" => fn() => $this->faker->email(),
      "password" => fn() => $this->faker->password()
    ]);
  }
}
