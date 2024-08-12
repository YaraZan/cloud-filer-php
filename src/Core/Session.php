<?php

namespace App\Core;

class Session
{
    public static function start(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function create(array $user): void
    {
        self::start();
        $_SESSION['uid'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        self::regenerate();
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

    public static function isAuthorized(): bool
    {
        self::start();
        return isset($_SESSION['uid']);
    }

    private static function regenerate(): void
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    public static function unset(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }
}
