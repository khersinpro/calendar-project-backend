<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class CsrfProtectionService
{
    const DEFAULT_EXPIRATION = 60 * 60 * 2;
    const ALLOWED_METHODS = ['POST', 'PUT', 'DELETE'];

    public function __construct(private string $secret, private string $environment)
    {
    }

    /**
     * Generate a new csrf token for the user
     * @param string $uniqueId - unique_id field from the user jwt
     * @return string
     */
    public function generateToken(string $uniqueId): string
    {
        $random = bin2hex(random_bytes(32));
        $message = $uniqueId . '!' . $random;
        $hmac = hash_hmac('sha256', $message, $this->secret);
        $csrfToken = $hmac . '.' . $random;

        return $csrfToken;
    }

    /**
     * Validate the csrf token
     * @param Request $request
     * @param string $uniqueId - unique_id field from the user jwt
     * @return null - if the token is valid
     * @throws AuthenticationException - if the token is invalid
     */
    public function validateToken(Request $request, string $uniqueId): null
    {
        if (!in_array($request->getMethod(), self::ALLOWED_METHODS)) {
            return null;
        }

        if (!$request->headers->has('x-csrf-token')) {
            throw new AuthenticationException('Invalid token');
        }

        $parts = explode('.', $request->headers->get('x-csrf-token'));

        if (count($parts) !== 2) {
            throw new AuthenticationException('Invalid token');
        }

        [$hmac, $random] = $parts;

        $message = $uniqueId . '!' . $random;
        $calculatedHmac = hash_hmac('sha256', $message, $this->secret); 
        
        if ($hmac !== $calculatedHmac) {
            throw new AuthenticationException('Invalid token');
        }

        return null;
    }

    /**
     * Create a csrf cookie
     * @param string $uniqueId - unique_id field from the user jwt
     * @param string $signature - the name of the cookie, default is x-csrf-token
     * @return Cookie
     */
    public function createCsrfCookie(string $uniqueId, string $signature = 'x-csrf-token'): Cookie
    {   
        $token = $this->generateToken($uniqueId);
        $cookie = new Cookie($signature, $token, time() + self::DEFAULT_EXPIRATION);

        return $cookie;
    }   
}