<?php

namespace App\Core;

/**
 * Abstract class for midddleware.
 */
abstract class Middleware 
{
    /** 
     * Handle incoming request
     * Being called before controller action and must contain middleware logic.
     * @param Request
     * @return void
     */
    public abstract function handle(Request $request): void;
}