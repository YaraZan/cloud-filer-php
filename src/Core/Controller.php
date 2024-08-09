<?php

namespace App\Core;

use Exception;

class Controller
{
    protected function handle(callable $action): Response
    {
        try {
            $result = $action();
            return new Response($result, 200);
        } catch (Exception $e) {
            return new Response(["message" => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
