<?php

namespace App\Core;

use App\Traits\Seedable;
use Exception;
use Faker\Factory as FakerFactory;
use PDOException;
use ReflectionClass;

/**
 * Base class for repository factories
 */
abstract class Factory
{
  protected $faker;
  protected $repository;
  private array $seededIds = [];
  private array $seededRows = [];

  public function __construct()
  {
    $this->faker = FakerFactory::create();
    $this->repository = $this->getMatchingRepository();
  }

  /**
   * Finds and instantiates the matching repository by factory class name
   *
   * @return mixed Repository instance
   */
  protected function getMatchingRepository()
  {
    $factoryClass = (new ReflectionClass($this))->getShortName();

    $repositoryClass = str_replace('Factory', 'Repository', $factoryClass);

    $repositoryClass = "App\\Repositories\\" . $repositoryClass;

    if (class_exists($repositoryClass)) {
      return new $repositoryClass();
    }

    throw new \Exception("Repository class $repositoryClass not found.");
  }

  private function generateRecord(array $data): array
  {
    foreach ($data as $column => $generate) {
      $record[$column] = $generate();
    }

    $this->repository->create($record);

    $record["id"] = $this->repository::getLastInsertedId();

    return $record;
  }

  protected function seed(int $numRecords, array $data): array
  {
    $this->repository->clearTable();
    $this->repository::beginTransaction();

    try {
      for ($i = 0; $i < $numRecords; $i++) {
        $this->seededRows[] = $this->generateRecord($data);
        $this->seededIds[] = $this->repository::getLastInsertedId();
      }

      $this->repository::commitTransaction();

      return $this->seededRows;
    } catch (PDOException) {
      $this->repository::rollbackTransaction();

      throw new Exception("Error while trying to seed");
    }
  }

  public abstract function work(int $numRecords): array;

  public function done(): void
  {
    $sql = "DELETE FROM " . $this->repository->table() . " WHERE id IN (" . implode(",", $this->seededIds) . ")";

    $this->repository::raw($sql);
  }
}
