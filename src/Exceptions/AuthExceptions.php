<?php

namespace App\Exceptions;

/**
 * Abstract class to determine user exceptions.
 */
abstract class AuthException extends BaseException
{
  public static function notAuthorized(): self
  {
    return self::errorUnauthorized();
  }

  public static function notAuthenticated(): self
  {
    return self::errorUnauthenticated();
  }
};

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
    return self::errorUnauthenticated();
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

class EmailException extends AuthException
{
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
class PasswordException extends AuthException
{
  public static function invalidFormat(): self
  {
    return self::errorBadArguments("Invalid password format. Password should be from 8 to 20 digits
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
