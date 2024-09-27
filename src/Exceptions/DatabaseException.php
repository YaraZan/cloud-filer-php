<?php

namespace App\Exceptions;

/**
 * Exception class to determine database exceptions.
 */
class DatabaseException extends BaseException {
  public static function errorExceutingQuery(): self
  {
    return self::errorInternal();
  }
};
