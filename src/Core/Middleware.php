<?php

namespace App\Core;

abstract class Middleware 
{
    protected abstract function handle(): Request;
}