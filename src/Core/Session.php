<?php

namespace App\Core;

use App\Utils\Tokenizer;

/**
 * Session management
 */
class Session
{
    /** Token expiration days range */
    private static int $expiresInDays = 7;

    /**
     * Start session
     * 
     * @return void
     */
    public static function start(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Create session instance 
     * 
     * @param array $user Stored user
     * @return string Session token
     */
    public static function create(array $user): string
    {
        self::start();

        $token = [
            "exp" => round(microtime(true) * 1000) + (self::$expiresInDays * 24 * 60 * 60 * 1000),
            "iat" => round(microtime(true)),
            "did" => hash('sha256', $_SERVER["HTTP_USER_AGENT"] . $_SERVER["REMOTE_ADDR"]),
            "user" => $user,
        ];

        $encodedToken = Tokenizer::encode($token);

        $_SESSION["token"] = $encodedToken;
        $_SESSION["rlim_" . $user["id"]] = [];

        self::regenerate();

        return $encodedToken;
    }

    /**
     * Get currently authorized user
     * 
     * @return array|null
     */
    public static function authorizedUser(): ?array
    {
        $token = self::get("token");

        return $token["user"] ?? null;
    }

    /**
     * Get session key
     * 
     * @param string $key Key name
     * @return mixed Key value
     */
    public static function get(string $key): mixed
    {
        self::start();
        return $_SESSION[$key] ?? null;
    }

    /**
     * Set session key
     * 
     * @param string $key Name of key
     * @param $value Key value
     * @return void
     */
    public static function set(string $key, $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Destroy session
     * 
     * @return void
     */
    public static function destroy(): void
    {
        self::start();
        session_unset();
        session_destroy();
    }

    /**
     * Regenerate session
     * 
     * @return void
     */
    private static function regenerate(): void
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }
}
