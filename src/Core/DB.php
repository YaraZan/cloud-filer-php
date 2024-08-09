<?php

namespace App\Core;

use PDO, PDOException;
use RuntimeException;

class DB
{
    protected string $tableName;
    protected string $primaryKey = "id";
    private static ?PDO $conn = null;

    public function table(): string
    {
        return $this->tableName;
    }

    private static function getConnection(): PDO
    {
        if (self::$conn === null) {
            try {
                self::$conn = new PDO(
                    "mysql:host=" . getenv("DB_HOST", "localhost"),
                    getenv("DB_USER", "root"),
                    getenv("DB_PASSWORD", "")
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

    public function findAll(): array
    {
        $sql = "SELECT * FROM " . $this->tableName;
        $res = $this->executeQuery($sql);

        return $res;
    }

    public function findOne(int $id): object
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE " . $this->primaryKey . " = ?";
        $res = $this->executeQuery($sql, [$id], false);

        return $res;
    }

    public function findWhere(string $query): array
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE " . $query;
        $res = $this->executeQuery($sql);

        return $res;
    }

    public function findOneWhere(string $query): object
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE " . $query;
        $res = $this->executeQuery($sql, [], false);

        return $res;
    }

    public function create(array $data): void {
        $sql = "INSERT INTO " . $this->tableName . " (";
        $columns = [];
        $placeholders = [];
        $params = [];

        foreach ($data as $key => $value) {
            $columns[] = $key;
            $placeholders[] = "?";
            $params[] = $value;
        }

        $sql .= implode(", ", $columns) . ") VALUES (" . implode(", ", $placeholders) . ")";

        $this->executeQuery($sql, $params);
    }

    public function update(int $id, array $data): void
    {
        $sql = "UPDATE " . $this->tableName . " SET ";

        $setClauses = [];
        $params = [];

        foreach ($data as $key => $value) {
            $setClauses[] = "$key = ?";
            $params[] = $value;
        }

        $sql .= implode(", ", $setClauses);
        $sql .= " WHERE " . $this->primaryKey . " = ?";
        $params[] = $id;

        $this->executeQuery($sql, $params);
    }

    public function delete(int $id): void
    {
        $sql = "DELETE FROM " . $this->tableName . " WHERE " . $this->primaryKey . " = ?";

        $this->executeQuery($sql, [$id]);
    }
}
