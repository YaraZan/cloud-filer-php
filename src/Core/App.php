<?php

namespace App\Core;

use Exception;

class App
{
    private static array $services;

    public static function registerServices(): void
    {
        self::$services['userService'] = new \App\Services\Impl\UserService();
    }

    public static function getService(string $name)
    {
        if (!isset(self::$services[$name])) {
            throw new Exception("Service not found: " . $name);
        }

        return self::$services[$name];
    }
}
