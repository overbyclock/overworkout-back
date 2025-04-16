<?php

namespace App\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService
{
  private string $secretKey;

  private const ACCESS_TOKEN_LIFETIME = 86400; // 24 horas 

  public function __construct(string $secretKey)
  {
    $this->secretKey = $secretKey;
  }

  public function generateToken(array $payload): array
  {
    $expiresAt = time()+self::ACCESS_TOKEN_LIFETIME;
    $payload['exp'] = $expiresAt;
    $payload['type'] = 'access';
    $token = JWT::encode($payload,$this->secretKey,'HS256');

    return [
      'token' => $token,
      'expiresAt' => $expiresAt
    ];
  }

  public function validateToken(string $token, string $type = 'access'): bool
  {
    try {
      $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
      
      // Verificar tipo de token
      if ($decoded->type !== $type) {
        return false;
      }

      // Verificar expiración
      if (isset($decoded->exp) && $decoded->exp < time()) {
        return false;
      }

      return true;
    } catch (\Exception $e) {
      return false;
    }
  }

  public function getPayload(string $token): array
  {
    $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
    return json_decode(json_encode($decoded),true);
  }  
}
