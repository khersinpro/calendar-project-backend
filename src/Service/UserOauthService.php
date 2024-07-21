<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserProvider;
use App\Enum\UserProviderEnum;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Token\AccessTokenInterface;

class UserOauthService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidationService $validationService
    )
    {
    }

    /**
     * Create a user from a google user in oauth flow
     * @param AccessTokenInterface $accessToken - the access token
     * @param GoogleUser $googleUser - the google user
     * @return User - the created user
     */
    public function createUserFromGoogle(AccessTokenInterface $accessToken, GoogleUser $googleUser)
    {
        $user = new User();
        $user->setEmail($googleUser->getEmail());
        $user->setFirstname($googleUser->getFirstname());
        $user->setLastname($googleUser->getLastname());
        
        $tokenExpire = (new \DateTime())->setTimestamp($accessToken->getExpires());

        $userProvider = new UserProvider();
        $userProvider->setType(UserProviderEnum::GOOGLE);
        $userProvider->setAccessToken($accessToken->getToken());
        $userProvider->setRefreshToken($accessToken->getRefreshToken());
        $userProvider->setTokenExpire($accessToken->getExpires());
        $userProvider->setUniqueId($googleUser->getId());
        $userProvider->setTokenExpire($tokenExpire);

        $user->addUserProvider($userProvider);

        $this->validationService->validate($user);
        $this->em->persist($user);
        $this->em->flush();       

        return $user;
    }

    /**
     * Create a google provider for a user
     * @param User $user - the user to create the provider for
     * @param AccessTokenInterface $accessToken - the access token
     * @param GoogleUser $googleUser - the google user
     * @return User
     */
    public function createUserGoogleProvider(User $user, AccessTokenInterface $accessToken, GoogleUser $googleUser): User
    {
        $tokenExpire = (new \DateTime())->setTimestamp($accessToken->getExpires());

        $googleUserProvider = new UserProvider();
        $googleUserProvider->setType(UserProviderEnum::GOOGLE);
        $googleUserProvider->setAccessToken($accessToken->getToken());
        $googleUserProvider->setRefreshToken($accessToken->getRefreshToken());
        $googleUserProvider->setUniqueId($googleUser->getId());
        $googleUserProvider->setTokenExpire($tokenExpire);
        $googleUserProvider->setUser($user);

        $this->validationService->validate($googleUserProvider);
        $this->em->persist($googleUserProvider);
        $this->em->flush();

        return $user;
    }

    /**
     * Update a google provider for a user, this will update the access token and refresh token, and the token expire
     * @param AccessTokenInterface $accessToken - the access token
     * @param GoogleUser $googleUser - the google user
     * @param UserProvider $userProvider - the user provider
     * @return User
     */
    public function updateUserGoogleProvider(AccessTokenInterface $accessToken, GoogleUser $googleUser, UserProvider $userProvider)
    {
        if ($userProvider->getType() !== UserProviderEnum::GOOGLE) {
            throw new \Exception('This is not a google provider');
        }

        $tokenExpire = (new \DateTime())->setTimestamp($accessToken->getExpires());

        $userProvider->setAccessToken($accessToken->getToken());
        $userProvider->setRefreshToken($accessToken->getRefreshToken());
        $userProvider->setUniqueId($googleUser->getId());
        $userProvider->setTokenExpire($tokenExpire);

        $this->validationService->validate($userProvider);
        $this->em->persist($userProvider);
        $this->em->flush();
    }
}