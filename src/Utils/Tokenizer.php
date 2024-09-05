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

    public static function createAccessToken(array $user): string
    {
        $accessToken = [
            "exp" => round(microtime(true) * 1000) + (ACCESS_TOKEN_EXP),
            "iat" => round(microtime(true)),
            "did" => hash('sha256', $_SERVER["HTTP_USER_AGENT"] . $_SERVER["REMOTE_ADDR"]),
            "user" => $user,
        ];
        return self::encode($accessToken);
    }

    public static function createRefreshToken(array $user): string
    {
        $refreshToken = [
            "exp" => round(microtime(true) * 1000) + (REFRESH_TOKEN_EXP),
            "iat" => round(microtime(true)),
            "uid" => $user["id"],
        ];
        return self::encode($refreshToken);
    }
}