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
        // Get the class name of the child factory (e.g., 'UserFactory')
        $factoryClass = (new ReflectionClass($this))->getShortName();

        // Replace 'Factory' with 'Repository' to get the repository class name
        $repositoryClass = str_replace('Factory', 'Repository', $factoryClass);

        // Complete the namespace (adjust to your actual namespace)
        $repositoryClass = "App\\Repositories\\" . $repositoryClass;

        // Check if the repository class exists and instantiate it
        if (class_exists($repositoryClass)) {
            return new $repositoryClass();
        }

        throw new \Exception("Repository class $repositoryClass not found.");
    }
    
    private function generateRecord(array $data): void
    {
        foreach ($data as $column => $generate) {
            $record[$column] = $generate();
        }

        $this->repository->create($record);
    }

    protected function seed(int $numRecords, array $data): void
    {
        $this->repository->clearTable();
        $this->repository::beginTransaction();

        try {
            for ($i=0; $i < $numRecords; $i++) { 
                $this->generateRecord($data);
                $this->seededIds[] = $this->repository::getLastInsertedId();
            }

            $this->repository::commitTransaction();
        } catch (PDOException) {
            $this->repository::rollbackTransaction();

            throw new Exception("Error while trying to seed");
        }
    }
    
    public abstract function work(int $numRecords): void;

    public function done(): void
    {
        $sql = "DELETE FROM " . $this->repository->table() . " WHERE id IN (" . implode(",", $this->seededIds) . ")";

        $this->repository::raw($sql);
    }
}