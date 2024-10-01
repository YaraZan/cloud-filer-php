<?php

namespace App\Core;

/** Require app config variables */
require_once __DIR__ . "/../Config/config.php";

use App\Exceptions\DatabaseException;
use PDO, PDOException;

/**
 * Base class for entity repositories
 * Used for connection to remote database.
 */
abstract class DB
{
    /** Table name */
    protected string $tableName;

    /** Name alias of primary key */
    protected string $primaryKey = "id";

    /** Database connection */
    private static ?PDO $conn = null;

    /**
     * Returns repository table name
     * @return string
     */
    public function table(): string
    {
        return $this->tableName;
    }

    /**
     * Used for connection to database
     * @return PDO
     */
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
            } catch (PDOException) {
              throw DatabaseException::errorExceutingQuery();
            }
        }
        return self::$conn;
    }

    /**
     * Executes sql string and returns database response
     *
     * @param string $sql
     * SQL query string

     * @param array $params
     * An array of insert params
     *
     * @param bool $fetchAll
     * Fetch all objects
     *
     * @return array
     * Database response
     */
    protected static function executeQuery(string $sql, array $params = [], bool $fetchAll = true): array
    {
        try {
            $stmt = self::getConnection()->prepare($sql);
            $stmt->execute($params);

            if (!$fetchAll) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                return $result ?: [];
            }

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result ?: [];
        } catch (\PDOException) {
            throw DatabaseException::errorExceutingQuery();
        }
    }

    /**
     * Begin a database transaction.
     * @return void
     */
    public static function beginTransaction(): void
    {
        self::getConnection()->beginTransaction();
    }

    /**
     * Commit the current transaction.
     * @return void
     */
    public static function commitTransaction(): void
    {
        self::getConnection()->commit();
    }

    /**
     * Roll back the current transaction.
     * @return void
     */
    public static function rollbackTransaction(): void
    {
        self::getConnection()->rollBack();
    }

    /**
     * Roll back the current transaction.
     * @return int Returned id
     */
    public static function getLastInsertedId(): ?int
    {
        return (int) self::getConnection()->lastInsertId() ?? null;
    }

    /**
     * Clear all rows from the current repository's table.
     * @return void
     */
    public function clearTable(): void
    {
        $sql = "DELETE FROM " . $this->tableName;

        self::executeQuery($sql);
    }

    /**
     * Execute raw SQL query
     *
     * @param string $sql Raw SQL query
     * @return array
     */
    public static function raw(string $sql): array
    {
        return self::executeQuery($sql);
    }

    /**
     * Fetch all records in repository
     * @return array
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM " . $this->tableName;
        $res = self::executeQuery($sql);

        return $res;
    }

    /**
     * Fetch one record in repository
     *
     * @param int $id
     * Id of searching record
     *
     * @return array
     */
    public function findOne(int $id): array
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE " . $this->primaryKey . " = ?";
        $res = self::executeQuery($sql, [$id], false);

        return $res;
    }

    /**
     * Fetch all records in repository that match condition
     *
     * @param string $query
     * SQL query with condition
     *
     * @return array
     */
    public function findAllWhere(string $query): array
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE " . $query;
        $res = self::executeQuery($sql);

        return $res;
    }

    /**
     * Fetch first record in repository that matches condition
     *
     * @param string $query
     * SQL query with condition
     *
     * @return array
     */
    public function findOneWhere(string $query): array
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE " . $query;
        $res = self::executeQuery($sql, [], false);

        return $res;
    }

    /**
     * Create a new record in repository
     *
     * @param array $data
     * An associative array representing a new record
     *
     * @return void
     */
    public function create(array $data): void
    {
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

        self::executeQuery($sql, $params);
    }

    /**
     * Update a record in repository
     *
     * @param int $id
     * Id of updating record
     *
     * @param array $data
     * An associative array with updated records data
     *
     * @return void
     */
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

        self::executeQuery($sql, $params);
    }

    /**
     * Delete a record in repository
     *
     * @param int $id
     * Id of deleting record
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $sql = "DELETE FROM " . $this->tableName . " WHERE " . $this->primaryKey . " = ?";

        self::executeQuery($sql, [$id]);
    }
}
