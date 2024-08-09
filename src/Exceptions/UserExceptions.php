<?php

namespace App\Exceptions;

use Exception;

class EmailDoesntExistException extends Exception 
{
    protected $message = 'User with provided email doesn`t exist';
}

class InvalidPasswordException extends Exception 
{
    protected $message = 'Invalid password';
}

class EmailAlreadyExistsException extends Exception 
{
    protected $message = 'User with provided email already exists';
}

class PasswordConfirmationException extends Exception 
{
    protected $message = 'Passwords doesn`t match';
}
?>