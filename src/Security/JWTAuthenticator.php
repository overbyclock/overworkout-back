<?php

namespace App\Security;

use App\Service\JWTService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class JWTAuthenticator extends AbstractAuthenticator
{
  private JWTService $jwtService;
  private UserProviderInterface $userProvider;

  public function __construct( JWTService $jwtService, UserProviderInterface $userProvider)
  {
    $this->jwtService = $jwtService;
    $this->userProvider = $userProvider;
  }

  public function supports(Request $request):bool
  {
    return $request->headers->has('Authorization');
  }

  public function authenticate(Request $request): SelfValidatingPassport
  {
    $authHeader = $request->headers->get('Authorization');
    if(!$authHeader||!str_starts_with($authHeader,'Bearer ')){
      throw new AuthenticationException('No token provided');
    }

    $token = substr($authHeader,7);

    return new SelfValidatingPassport(new UserBadge($token, function($token){
      if(!$this->jwtService->validateToken($token)){
        throw new AuthenticationException('Invalid Token');
      }
      $payload = $this->jwtService->getPayload($token);
      return $this->userProvider->loadUserByIdentifier($payload['email']);
    }));
  }

  public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
  {
    return new JsonResponse(['error'=>'Authentication failed'],JsonResponse::HTTP_UNAUTHORIZED);
  }

  public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?JsonResponse
  {
    return null;
  }

  public function start(Request $request, AuthenticationException $authException=null):JsonResponse
  {
    return new JsonResponse(['error'=>'Authentication required'],JsonResponse::HTTP_UNAUTHORIZED);
  }
}