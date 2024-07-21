<?php
namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Cookie;

class AuthCookieService
{
    const DEFAULT_EXPIRATION = 60 * 60 * 24 * 14;
    public function __construct(#[Autowire('%kernel.environment%')] private string $environment) { }

    /**
     * Create an auth cookie
     * @param string $token - the jwt token to create the cookie with
     * @param int $expiration - the expiration of the cookie, default is 14 days
     * @return Cookie - the created cookie
     */
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

    /**
     * Delete the auth cookie, the cookie will expire immediately
     * @return Cookie - the deleted cookie
     */
    static function deleteAuthCookie(): Cookie
    {
        return new Cookie('x-auth-token', '', time() - 3600);
    }
}