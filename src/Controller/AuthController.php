<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\AuthCookieService;
use App\Service\JwtService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api/auth')]
class AuthController extends AbstractController
{
    public function __construct(private JwtService $jwtService, private AuthCookieService  $authCookieService) { }

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

        $response = $this->json(['user' => $user], JsonResponse::HTTP_OK, [], ['groups' => 'user.read']);
        
        $response->headers->setCookie($this->authCookieService->createAuthCookie($token));

        return $response;
    }

    #[Route('/google', name: 'app_google_login', methods: ['GET'])]
    public function googleLogin(ClientRegistry $clientRegistry)
    {
        return $clientRegistry->getClient('google')->redirect(['email', 'profile'], []);
    }

    #[Route('/google/callback', name: 'app_google_login_callback', methods: ['GET'])]
    public function googleLoginCallback(ClientRegistry $clientRegistry, UserRepository $userRepository, EntityManagerInterface $em)
    {
        /** @var \KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient $client */
        $client = $clientRegistry->getClient('google');

        try {
            /** @var \League\OAuth2\Client\Provider\GoogleUser $googleUser */
            $googleUser = $client->fetchUser();

            $user = $userRepository->findOneBy(['email' => $googleUser->getEmail()]);

            if (!$user) {
                $user = new User();
                $user->setEmail($googleUser->getEmail());
                $user->setFirstname($googleUser->getFirstname());
                $user->setLastname($googleUser->getLastname());
                $user->setGoogleId($googleUser->getId());
                $em->persist($user);
                $em->flush();
            }
            
            $token = $this->jwtService->generateToken([
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles()
            ]);

            $response = $this->json(['user' => $user], JsonResponse::HTTP_OK, [], ['groups' => 'user.read']);
            $response->headers->setCookie($this->authCookieService->createAuthCookie($token));
            return $response;
        }
        catch (Exception $e) {
            return $this->json($e->getMessage(), JsonResponse::HTTP_UNAUTHORIZED);
        }
    }
}
