<?php
namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Cookie;

class AuthCookieService
{
    const DEFAULT_EXPIRATION = 60 * 60 * 24 * 14;
    public function __construct(#[Autowire('%kernel.environment%')] private string $environment) { }

    public function createAuthCookie(string $token, int $expiration = self::DEFAULT_EXPIRATION): Cookie
    {
        $cookie = new Cookie(
            'x-auth-token',
            $token,
            time() + $expiration,
            '/',
            null,
            $this->environment === 'prod',
            true,
            false
        );

        return $cookie;

    }

    static function deleteAuthCookie(): Cookie
    {
        return new Cookie('x-auth-token', '', time() - 3600);
    }
}