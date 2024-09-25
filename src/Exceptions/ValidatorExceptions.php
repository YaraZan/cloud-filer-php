<?php

namespace App\Exceptions;

/**
 * Abstract class to determine validator exceptions.
 */
abstract class ValidatorException extends BaseException {};

/**
 * Property exceptions
 */
class ValidatorPropertyException extends ValidatorException
{
    public static function isRequired(string $propertyName): self
    {
        return self::errorBadArguments(
            sprintf(
                "The '%s' property is required!",
                $propertyName
            )
        );
    }

    public static function lenghtOverLimit(string $propertyName): self
    {
        return self::errorBadArguments(
            sprintf(
                "The '%s' property's length is over it's maximum limit!",
                $propertyName
            )
        );
    }

    public static function lenghtUnderLimit(string $propertyName): self
    {
        return self::errorBadArguments(
            sprintf(
                "The '%s' property's length is under it's minimum limit!",
                $propertyName
            )
        );
    }
}
