<?php

namespace App\Security;

use App\Service\JwtService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiJwtAuthenticator extends AbstractAuthenticator
{
    public function __construct(private JwtService $jwtService) { }

    public function supports(Request $request): ?bool
    {
        return $request->headers->get('Authorization');
    }

    public function authenticate(Request $request): Passport
    {
        $tokenHeader = $request->headers->get('Authorization');

        if (!$tokenHeader) throw new AuthenticationException('No token provided');
        

        $token = str_replace('Bearer ', '', $tokenHeader);
        $decodedToken = $this->jwtService->decodeToken($token);

        if (!$decodedToken) throw new AuthenticationException('Invalid token');

        return new SelfValidatingPassport(new UserBadge($decodedToken->email));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse($exception->getMessage(), JsonResponse::HTTP_UNAUTHORIZED);
    }

    //    public function start(Request $request, AuthenticationException $authException = null): Response
    //    {
    //        /*
    //         * If you would like this class to control what happens when an anonymous user accesses a
    //         * protected page (e.g. redirect to /login), uncomment this method and make this class
    //         * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
    //         *
    //         * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
    //         */
    //    }
}
