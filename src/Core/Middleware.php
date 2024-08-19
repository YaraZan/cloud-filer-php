<?php

namespace App\Core;

abstract class Middleware 
{
    public abstract function handle(Request $request): void;
}