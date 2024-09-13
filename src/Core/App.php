<?php

namespace App\Core;

use Exception;

/**
 * A service resgistry class.
 */
class App
{
    /** Array of registered services */
    private static array $services;

    /**
     * Call to this function registrates all defined services.
     * @return void
     */
    public static function registerServices(): void
    {
        self::$services['userService'] = new \App\Services\Impl\UserService();
        self::$services['adminService'] = new \App\Services\Impl\AdminService();
    }

    /**
     * Returns service instance by it's name.
     * @return mixed
     */
    public static function getService(string $name): mixed
    {
        if (!isset(self::$services[$name])) {
            throw new Exception("Service not found: " . $name);
        }

        return self::$services[$name];
    }
}
