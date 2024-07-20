<?php 

namespace App\Service;

use App\Entity\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Ramsey\Uuid\Uuid;
use stdClass;

class JwtService
{
    public function __construct(private string $privateKeyPath, private string $publicKeyPath, private string $passphrase){ }

    public function generateToken(User $user): string
    {
        if (!$user instanceof User) {
            throw new \Exception('Invalid user object');
        }

        $privateKey = openssl_pkey_get_private(file_get_contents($this->privateKeyPath), $this->passphrase);
        return JWT::encode([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'unique_id' => Uuid::uuid4()->toString()
        ], $privateKey, 'RS256');
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