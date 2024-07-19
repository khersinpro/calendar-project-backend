<?php

namespace App\Security;

use App\Repository\UserRepository;
use App\Service\AuthCookieService;
use App\Service\EmailNormalizerService;
use App\Service\JwtService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class BaseAuthenticator extends AbstractAuthenticator
{

    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $hasher,
        private JwtService $jwtService
    ) 
    {  } 

    public function supports(Request $request): ?bool
    {
        return $request->getPathInfo() === '/api/auth/login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !$data['email'] || !$data['password']) {
            throw new AuthenticationException('Invalid credentials');
        }

        $user = $this->userRepository->findOneBy([
            'email' => EmailNormalizerService::normalizeEmail($data['email'])
        ]);

        if (!$user || !$user->getPassword()) {
            throw new AuthenticationException('Invalid credentials');
        }

        if (!$this->hasher->isPasswordValid($user, $data['password'])) {
            throw new AuthenticationException('Invalid credentials');
        }

        return new SelfValidatingPassport(new UserBadge($user->getEmail()));
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
}
