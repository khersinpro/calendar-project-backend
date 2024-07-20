<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\UserProviderEnum;
use App\Repository\UserProviderRepository;
use App\Repository\UserRepository;
use App\Service\AuthCookieService;
use App\Service\CsrfProtectionService;
use App\Service\JwtService;
use App\Service\UserOauthService;
use Exception;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api/auth')]
class AuthController extends AbstractController
{
    public function __construct(
        private JwtService $jwtService, 
        private AuthCookieService  $authCookieService
    ) { }

    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return $this->json('Bad credentials', JsonResponse::HTTP_UNAUTHORIZED);
        }

        $token = $this->jwtService->generateToken($user);

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
    public function googleLoginCallback(
        ClientRegistry $clientRegistry, 
        UserRepository $userRepository, 
        UserProviderRepository $userProviderRepository, 
        UserOauthService $userOauthService
    )
    {
        /** @var \KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient $client */
        $client = $clientRegistry->getClient('google');

        try {
            $accessToken = $client->getAccessToken();
            
            /** @var \League\OAuth2\Client\Provider\GoogleUser $googleUser */
            $googleUser = $client->fetchUserFromToken($accessToken);

            $user = $userRepository->findOneBy(['email' => $googleUser->getEmail()]);

            if (!$user) {
                $user = $userOauthService->createUserFromGoogle($accessToken, $googleUser);
            } else {
                $existingGoogleUserProvider = $userProviderRepository->findOneBy([
                    'user_id' => $user->getId(), 
                    'type' => UserProviderEnum::GOOGLE
                ]);

                if (!$existingGoogleUserProvider) {
                    $userOauthService->createUserGoogleProvider($user, $accessToken, $googleUser);
                } else {
                    $userOauthService->updateUserGoogleProvider($accessToken, $googleUser, $existingGoogleUserProvider);
                }
            }

            $token = $this->jwtService->generateToken($user);

            $response = $this->json(['user' => $user], JsonResponse::HTTP_OK, [], ['groups' => 'user.read']);
            $response->headers->setCookie($this->authCookieService->createAuthCookie($token));
            return $response;
        }
        catch (Exception $e) {
            return $this->json($e->getMessage(), JsonResponse::HTTP_UNAUTHORIZED);
        }
    }

    #[Route('/csrf', name: 'app_csrf', methods: ['GET'])]
    public function csrf(CsrfProtectionService $csrfProtectionService, Request $request): JsonResponse
    {
        $response = new JsonResponse('', JsonResponse::HTTP_NO_CONTENT);

        if ($uniqueId = $request->attributes->get('unique_id')) {
            $response->headers->setCookie($csrfProtectionService->createCsrfCookie($uniqueId));
        }

        return $response;
    }
}
