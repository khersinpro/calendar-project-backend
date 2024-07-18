<?php 

namespace App\Service;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class JwtService
{
    public function __construct(private string $privateKeyPath, private string $publicKeyPath, private string $passphrase){ }

    public function generateToken(array $payload): string
    {
        $privateKey = openssl_pkey_get_private(file_get_contents($this->privateKeyPath), $this->passphrase);
        return JWT::encode($payload, $privateKey, 'RS256');
    }

    public function decodeToken(string $token): ?stdClass
    {
        try {
            $publicKey = openssl_pkey_get_public(file_get_contents($this->publicKeyPath));
            return JWT::decode($token, new Key($publicKey, 'RS256'));
        } catch (Exception $e) {
            return null;
        }
    }
}