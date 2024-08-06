<?php

namespace App\Core;

use Exception;

class App
{
    private static array $registry;

    public function __construct()
    {
        // Register services here
    }

    public static function registerService(string $name, $service): void
    {
        self::$registry[$name] = $service;
    }

    public static function getService(string $name)
    {
        if (!isset(self::$registry[$name])) {
            throw new Exception("Service not found: " . $name);
        }

        return self::$registry[$name];
    }
}
