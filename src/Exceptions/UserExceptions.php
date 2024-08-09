<?php

namespace App\Exceptions;

use Exception;

class EmailDoesntExistException extends Exception 
{
    protected $message = 'User with provided email doesn`t exist';
}

class InvalidEmailFormatException extends Exception 
{
    protected $message = 'Invalid email format. Email should match pattern: example@mail.com';
}

class InvalidPasswordException extends Exception 
{
    protected $message = 'Invalid password';
}

class InvalidPasswordFormatException extends Exception 
{
    protected $message = 'Invalid password format. Password should be 12 digits 
    lenght, including numbers and spec. symbols: /, #, $, %, *, _, -';
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