<?php

namespace App\Utils;

class Validator
{
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function hashPassword(string $password): string
    {
        $secret = getenv("HASH_SECRET");
        $passwordWithSecret = $password . $secret;

        return password_hash($passwordWithSecret, PASSWORD_BCRYPT);
    }

    public static function verifyPasswords(string $password, string $hash): bool
    {
        $secret = getenv("HASH_SECRET");
        $passwordWithSecret = $password . $secret;

        return password_verify($passwordWithSecret, $hash);
    }
}
