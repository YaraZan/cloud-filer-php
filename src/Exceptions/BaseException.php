<?php

namespace App\Exceptions;

use Exception;

/**
 * Base application class for exceptions.
 */
class BaseException extends Exception {

    // 404 Not Found error
    protected static function errorNotFound(string $message): self
    {
        return new self($message, 404);
    }

    // 403 Forbidden error (Unauthorized Access)
    protected static function errorUnauthorized(string $message): self
    {
        return new self($message, 403);
    }

    // 409 Conflict error (Duplicate entry or conflict)
    protected static function errorConflict(string $message): self
    {
        return new self($message, 409);
    }

    // 500 Internal Server error (Generic server failure)
    protected static function errorInternal(string $message): self
    {
        return new self($message, 500);
    }

    // 400 Bad Request error (Bad data or invalid arguments)
    protected static function errorBadArguments(string $message): self
    {
        return new self($message, 400);
    }

    // 419 Session Expired error
    protected static function errorExpired(string $message): self
    {
        return new self($message, 419);
    }
}
