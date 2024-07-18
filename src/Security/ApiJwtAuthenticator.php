<?php

namespace App\Security;

use App\Service\AuthCookieService;
use App\Service\JwtService;
use Symfony\Component\HttpFoundation\Cookie;
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
        return $request->cookies->has('x-auth-token') && $request->cookies->get('x-auth-token');
    }

    public function authenticate(Request $request): Passport
    {
        $token = $request->cookies->get('x-auth-token');
        
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
        $response = new JsonResponse($exception->getMessage(), JsonResponse::HTTP_UNAUTHORIZED);

        if ($request->cookies->has('x-auth-token')) {
            $response->headers->setCookie(AuthCookieService::deleteAuthCookie());
        }

        return $response;
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
