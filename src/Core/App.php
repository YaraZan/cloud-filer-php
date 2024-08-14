<?php

namespace App\Core;

use App\Services\Impl\UserService;
use Exception;

class App
{
    private static array $registry;

    public function __construct()
    {
        self::registerService('userService', UserService::class);
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
