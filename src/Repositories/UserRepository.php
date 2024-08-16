<?php

namespace App\Repositories;

use App\Core\DB;

class UserRepository extends DB
{
    protected static string $tableName = "users";

    public static function findAll(): array
    {
        $sql = "SELECT id, name, email from " . self::$tableName;
        $res = self::executeQuery($sql);

        return $res;
    }

    public static function findOne(int $id): array
    {
        $sql = "SELECT id, name, email, bio from " . self::$tableName;
        $res = self::executeQuery($sql, [$id], false);

        return $res;
    }
}
