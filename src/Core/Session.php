<?php

namespace App\Core;

use App\Exceptions\TokenInvalidException;
use App\Repositories\UserRepository;
use App\Utils\Tokenizer;
use Exception;

class Session
{
    private static int $expiresInDays = 7;

    public static function start(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function create(array $user): void
    {
        self::start();

        $token = [
            "exp" => round(microtime(true) * 1000) + (self::$expiresInDays * 24 * 60 * 60 * 1000),
            "iat" => round(microtime()),
            "did" => hash('sha256', $_SERVER["HTTP_USER_AGENT"] . $_SERVER["REMOTE_ADDR"]),
            "user" => $user,
        ];

        $_SESSION["token"] = Tokenizer::encode($token);
        $_SESSION["rlim_" . $user["id"]] = [];

        self::regenerate();
    }

    public static function authorizedUser(): array | null
    {
        $token = self::get("token"); 

        if (!isset($token)) {
            throw new TokenInvalidException();
        }

        return $token["user"] ?? null;
    }

    public static function get(string $key)
    {
        self::start();
        return $_SESSION[$key] ?? null;
    }

    public static function set(string $key, $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function destroy(): void
    {
        self::start();
        session_unset();
        session_destroy();
    }

    private static function regenerate(): void
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }
}
