<?php

namespace App\Core;

require_once __DIR__ . "/../Config/config.php";

use PDO, PDOException;
use RuntimeException;

class DB
{
    protected static string $tableName;
    protected static string $primaryKey = "id";
    private static ?PDO $conn = null;

    public static function table(): string
    {
        return self::$tableName;
    }

    private static function getConnection(): PDO
    {
        if (self::$conn === null) {
            try {
                self::$conn = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                    DB_USER,
                    DB_PASS
                );
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
        return self::$conn;
    }

    protected function executeQuery(string $sql, array $params = [], bool $fetchAll = true): mixed
    {
        try {
            $stmt = self::getConnection()->prepare($sql);
            $stmt->execute($params);
    
            if (!$fetchAll) {
                return $stmt->fetchObject(PDO::FETCH_ASSOC);
            }
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \RuntimeException("Database query error: " . $e->getMessage());
        }
    }

    public static function findAll(): array
    {
        $sql = "SELECT * FROM " . self::$tableName;
        $res = self::executeQuery($sql);

        return $res;
    }

    public static function findOne(int $id): array
    {
        $sql = "SELECT * FROM " . self::$tableName . " WHERE " . self::$primaryKey . " = ?";
        $res = self::executeQuery($sql, [$id], false);

        return $res;
    }

    public static function findWhere(string $query): array
    {
        $sql = "SELECT * FROM " . self::$tableName . " WHERE " . $query;
        $res = self::executeQuery($sql);

        return $res;
    }

    public static function findOneWhere(string $query): array
    {
        $sql = "SELECT * FROM " . self::$tableName . " WHERE " . $query;
        $res = self::executeQuery($sql, [], false);

        return $res;
    }

    public static function create(array $data): void {
        $sql = "INSERT INTO " . self::$tableName . " (";
        $columns = [];
        $placeholders = [];
        $params = [];

        foreach ($data as $key => $value) {
            $columns[] = $key;
            $placeholders[] = "?";
            $params[] = $value;
        }

        $sql .= implode(", ", $columns) . ") VALUES (" . implode(", ", $placeholders) . ")";

        self::executeQuery($sql, $params);
    }

    public static function update(int $id, array $data): void
    {
        $sql = "UPDATE " . self::$tableName . " SET ";

        $setClauses = [];
        $params = [];

        foreach ($data as $key => $value) {
            $setClauses[] = "$key = ?";
            $params[] = $value;
        }

        $sql .= implode(", ", $setClauses);
        $sql .= " WHERE " . self::$primaryKey . " = ?";
        $params[] = $id;

        self::executeQuery($sql, $params);
    }

    public static function delete(int $id): void
    {
        $sql = "DELETE FROM " . self::$tableName . " WHERE " . self::$primaryKey . " = ?";

        self::executeQuery($sql, [$id]);
    }
}
