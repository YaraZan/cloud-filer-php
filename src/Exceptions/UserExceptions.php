<?php

namespace App\Exceptions;

/**
 * Abstract class to determine user exceptions.
 */
abstract class UserException extends BaseException {};

/**
 * Email exceptions.
 */
class EmailException extends UserException {
    public static function invalidFormat(): self
    {
        return self::errorBadArguments("Invalid email format. Email should match pattern: example@mail.com",);
    }

    public static function alreadyExists(): self
    {
        return self::errorBadArguments("User with provided email already exists",);
    }

    public static function doesntExists(): self
    {
        return self::errorBadArguments("User with provided email doesn`t exist",);
    }
};

/**
 * Password exceptions.
 */
class PasswordException extends UserException {
    public static function invalidFormat(): self
    {
        return self::errorBadArguments("Invalid password format. Password should be 12 digits
    lenght, including numbers and spec. symbols: /, #, $, %, *, _, -");
    }

    public static function incorrect(): self
    {
        return self::errorBadArguments("Password incorrect");
    }

    public static function doesntMatch(): self
    {
        return self::errorBadArguments("Passwords doesnt match");
    }
}
