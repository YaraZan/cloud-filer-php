<?php

namespace App\Utils;

require_once __DIR__ . "/../Config/config.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Tokenizer 
{
    public static function encode(mixed $payload): string
    {
        return JWT::encode($payload, JWT_SECRET, 'HS256');
    }

    public static function decode(string $jwt): mixed
    {
        return JWT::decode($jwt, new Key(JWT_SECRET, 'HS256'));
    }
}