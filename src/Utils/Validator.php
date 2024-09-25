<?php

namespace App\Utils;

use App\Exceptions\EmailException;
use App\Exceptions\PasswordException;
use App\Exceptions\ValidatorPropertyException;

class Validator
{
  /**
   * Validates properties passed with **request**
   * to match some patterns.
   *
   * @param mixed $payload
   * @return bool
   */
  public static function validate(array $conditions, array $payload): array
  {
    foreach ($conditions as $key => $rules) {
      if (in_array("required", $rules) && !array_key_exists($key, $payload)) {
        throw ValidatorPropertyException::isRequired($key);
      }

      if (key_exists($key, $payload)) {
        foreach ($rules as $rule) {
          if (is_callable($rule)) {
            $rule($payload[$key]);
          } else if (preg_match('/^max:\d+$/', $rule)) {
            $maxConditionArr = explode(":", $rule);
            $limit = end($maxConditionArr);

            if (strlen($payload[$key]) > $limit) {
              throw ValidatorPropertyException::lenghtOverLimit($key);
            }
          } else if (preg_match('/^min:\d+$/', $rule)) {
            $minConditionArr = explode(":", $rule);
            $limit = end($minConditionArr);

            if (strlen($payload[$key]) < $limit) {
              throw ValidatorPropertyException::lenghtUnderLimit($key);
            }
          } else if ($rule === "no-whitespace") {
            $payload[$key] = trim($payload[$key]);
          }
        }
      }
    }

    return $payload;
  }

  public static function validateEmail(string $email): bool
  {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw EmailException::invalidFormat();
    }
    return true;
  }

  public static function validatePassword(string $password): bool
  {
    $pattern = '/^(?=.*[0-9])(?=.*[\/\#\$\%\*\_\-])[A-Za-z0-9\/\#\$\%\*\_\-]{8,20}$/';

    if (!preg_match($pattern, $password)) {
      throw EmailException::invalidFormat();
    }
    return true;
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
