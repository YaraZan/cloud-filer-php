<?php

namespace App\Utils;

use Exception;

class Validator
{
    /**
     * Validates properties passed with **request** 
     * to match some patterns.
     * 
     * @param mixed $payload
     * @return bool
     */
    public static function validate(array $rules, array $payload): void
    {
        // Iterate through payload
        foreach ($rules as $key => $rule) {
            $conditionsArr = explode("|", $rule);

            // Check if property required
            if (in_array("required", $conditionsArr)) {
                if (!key_exists($key, $payload)) {
                    throw new Exception("The " . $key . " property is required!", 400);
                }
            }

            // Checks for other non-required properties
            if (key_exists($key, $payload)) {
                // Check if property has limitations on max length 
                $maxMatchesArr = array_filter($conditionsArr, function ($match) {
                    return preg_match('/^max:\d+$/', $match);
                });
                if (!empty($maxMatchesArr)) {
                    $maxConditionArr = explode(":", end($maxMatchesArr));
                    $limit = end($maxConditionArr);

                    if (strlen($payload[$key]) > $limit) {
                        throw new Exception("The " . $key . " property's length is over it's maximum limit!", 400);
                    }
                }
                
                // Check if property has limitations on min length 
                $minMatchesArr = array_filter($conditionsArr, function ($match) {
                    return preg_match('/^min:\d+$/', $match);
                });
                if (!empty($minMatchesArr)) {
                    $minConditionArr = explode(":", end($minMatchesArr));
                    $limit = end($minConditionArr);

                    if (strlen($payload[$key]) < $limit) {
                        throw new Exception("The " . $key . " property's length is under it's minimum lilit!", 400);
                    }
                }
            }
        }
    }

    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function validatePassword(string $password): bool
    {
        $pattern = '/^(?=.*[0-9])(?=.*[\/\#\$\%\*\_\-])[A-Za-z0-9\/\#\$\%\*\_\-]{12,26}$/';

        return preg_match($pattern, $password) === 1;
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
