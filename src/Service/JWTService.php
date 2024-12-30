<?php

namespace App\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService
{
  private string $secretKey;

  public function __construct(string $secretKey)
  {
    $this->secretKey = $secretKey;
  }

  public function generateToken(array $payload): array
  {
    $expirationTime = time() + (24 * 60 * 60);
    $payload['exp'] = $expirationTime;
    $token = JWT::encode($payload, $this->secretKey, 'HS256');
    return [
      'token' => $token,
      'expiresAt' => $expirationTime
    ];
  }

  public function validateToken(string $token): bool
  {
    try {
      JWT::decode($token, new Key($this->secretKey, 'HS256'));
      return true;
    } catch (\Exception $e) {
      return false;
    }
  }

  public function getPayload(string $token): array
  {
    return (array) JWT::decode($token, new Key($this->secretKey, 'HS256'));
  }
}
