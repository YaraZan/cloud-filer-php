<?php

namespace App\Core;

use PDO, PDOException;

abstract class DB {
    protected string $tableName;
    protected string $primaryKey = "id";
    private PDO $conn;

    public function __construct()
    {
        $this->getConnection();
    }

    private function getConnection(): void
    {
        try {
            $this->conn = new PDO(
                "mysql:host=" . getenv("DB_HOST", "localhost"),
                getenv("DB_USER", "root"),
                getenv("DB_PASSWORD", "")
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    protected function executeQuery(string $sql, array $params = []): array
    {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findBy(string $query): string
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE " . $query;
        $res = $this->executeQuery($sql);

        return json_encode($res);
    }

    public function findOneBy(string $query): string
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE " . $query . " LIMIT 1";
        $res = $this->executeQuery($sql);

        return json_encode($res);
    }

    public function findAll(): string
    {
        $sql = "SELECT * FROM " . $this->tableName;
        $res = $this->executeQuery($sql);

        return json_encode($res);
    }

    public function find(int $id): string
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE " . $this->primaryKey . " = ?";
        $res = $this->executeQuery($sql, [$id]);

        return json_encode($res);
    }
}
