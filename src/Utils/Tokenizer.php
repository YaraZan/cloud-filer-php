<?php

namespace App\Utils;

use App\Exceptions\TokenException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Tokenizer
{
  public static function encode(mixed $payload): string
  {
    try {
      return JWT::encode($payload, $_ENV["TOKEN_SECRET"], 'HS256');
    } catch (\Throwable) {
      throw TokenException::encodingTokenException();
    }
  }

  public static function decode(string $jwt): array
  {
    try {
      $decoded = JWT::decode($jwt, new Key($_ENV["TOKEN_SECRET"], 'HS256'));
      return json_decode(json_encode($decoded), true);
    } catch (\Throwable) {
      throw TokenException::decodingTokenException();
    }
  }

  public static function createAccessToken(array $user): string
  {
    try {
      $accessToken = [
        "exp" => round(microtime(true) * 1000) + ($_ENV["TOKEN_ACCESS_EXP"]),
        "iat" => round(microtime(true)),
        "did" => hash('sha256', $_SERVER["HTTP_USER_AGENT"] . $_SERVER["REMOTE_ADDR"]),
        "user" => $user,
      ];
      return self::encode($accessToken);
    } catch (\Throwable) {
      throw TokenException::createTokenException();
    }
  }

  public static function createRefreshToken(array $user): string
  {
    try {
      $refreshToken = [
        "exp" => round(microtime(true) * 1000) + ($_ENV["TOKEN_REFRESH_EXP"]),
        "iat" => round(microtime(true)),
        "uid" => $user["id"],
      ];
      return self::encode($refreshToken);
    } catch (\Throwable) {
      throw TokenException::createTokenException();
    }
  }

  /**
   * Retirns token pair as array where first element
   * is `access_token` and second is `refresh_token`.
   */
  public static function createTokenPair(array $user): array
  {
    return [self::createAccessToken($user), self::createRefreshToken($user)];
  }
}
