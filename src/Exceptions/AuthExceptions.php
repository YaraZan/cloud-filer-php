<?php

namespace App\Exceptions;

/**
 * Abstract class to determine user exceptions.
 */
abstract class AuthException extends BaseException {};

/**
 * Token exceptionxs.
 */
class TokenException extends AuthException
{
    public static function doesntExist(): self
    {
        return self::errorUnauthenticated();
    }

    public static function invalid(): self
    {
        return self::errorUnauthenticated();
    }

    public static function refreshTokenExpired(): self
    {
        return self::errorExpired("Session expired");
    }

    public static function encodingTokenException(): self
    {
        return self::errorInternal();
    }

    public static function decodingTokenException(): self
    {
        return self::errorInternal();
    }

    public static function createTokenException(): self
    {
        return self::errorInternal();
    }
}

class SessionException extends AuthException
{
  public static function noUser(): self
  {
    return self::errorUnauthenticated();
  }

  public static function wrongUser(): self
  {
    return self::errorUnauthenticated();
  }
}
