<?php

namespace App\Exceptions;

use Exception;

class NotAuthorizedException extends Exception
{
    protected $code = 401;
    protected $message = 'Not authorized';
}

class TokenInvalidException extends Exception
{
    protected $code = 401;
    protected $message = 'Not authorized';
}

class TokenExpiredException extends Exception
{
    protected $code = 419;
    protected $message = 'Token expired';
}