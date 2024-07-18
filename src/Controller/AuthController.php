<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\JwtService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api/auth')]
class AuthController extends AbstractController
{
    public function __construct(private JwtService $jwtService) { }

    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return $this->json('Bad credentials', JsonResponse::HTTP_UNAUTHORIZED);
        }

        $token = $this->jwtService->generateToken([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles()
        ]);

        return $this->json(['token' => $token, 'user' => $user], JsonResponse::HTTP_OK, [], ['groups' => 'user.read']);
    }
}
