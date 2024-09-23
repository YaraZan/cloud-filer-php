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

    // 403 authentication error (Unauthenticated Access)
    protected static function errorUnauthenticated(): self
    {
        return new self("Not authenticated", 401);
    }

    // 403 Forbidden error (Unauthorized Access)
    protected static function errorUnauthorized(): self
    {
        return new self("Not authorized", 403);
    }

    // 409 Conflict error (Duplicate entry or conflict)
    protected static function errorConflict(string $message): self
    {
        return new self($message, 409);
    }

    // 500 Internal Server error (Generic server failure)
    protected static function errorInternal(): self
    {
        return new self("Server currently unavailable or cannot
        process this action. Please try it later.", 500);
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
