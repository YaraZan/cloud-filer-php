<?php

namespace App\Repositories;

use App\Core\DB;

class UserRepository extends DB
{
    protected string $tableName = "users";

    public function findAll(): array
    {
        $sql = "SELECT id, name, email from " . $this->tableName;
        $res = $this->executeQuery($sql);

        return $res;
    }

    public function findOne(int $id): object
    {
        $sql = "SELECT id, name, email, bio from " . $this->tableName;
        $res = $this->executeQuery($sql, [$id], false);

        return $res;
    }
}
