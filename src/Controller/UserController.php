<?php

namespace App\Controller;

use App\DTO\PaginationDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/user')]
class UserController extends AbstractController
{
    public function __construct(private UserRepository $repository, private ValidationService $validationService, private EntityManagerInterface $em)       
    {}

    #[Route(name: 'user.list', methods: ['GET'])]
    public function list(#[MapQueryString] ?PaginationDTO $paginationDTO): JsonResponse
    {
        $user = $this->repository->findAllPaginated($paginationDTO?->page, $paginationDTO?->limit);

        return $this->json($user, JsonResponse::HTTP_OK, [], ['groups' => 'user.read']);
    }

    #[Route('/{id}', name: 'user.show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(User $user): JsonResponse
    {
        return $this->json($user, JsonResponse::HTTP_OK, [], ['groups' => 'user.read']);
    }

    #[Route('/{id}', name: 'user.update', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function update(Request $request, User $user, SerializerInterface $serializer, UserPasswordHasherInterface $hasher): JsonResponse
    {   
        $data = json_decode($request->getContent(), true);

        $serializer->deserialize ($request->getContent(), User::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $user,
            'groups' => ['user.update'] 
        ]);

        $error = $this->validationService->validate($user);
        if ($error) return $error;

        if (isset($data['password']) && $data['password'] !== null) {
            $user->setPassword($hasher->hashPassword($user, $user->getPassword()));
        }
        
        $this->em->persist($user);
        $this->em->flush();

        return $this->json($user, JsonResponse::HTTP_OK, [], ['groups' => 'user.read']);
    }

    #[Route('/{id}', name: 'user.delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(User $user): JsonResponse
    {
        $this->em->remove($user);
        $this->em->flush();

        return $this->json('', JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route('/me', name: 'user.me', methods: ['GET'])]
    public function me(#[CurrentUser] User $user): JsonResponse
    {
        return $this->json($user, JsonResponse::HTTP_OK, [], ['groups' => 'user.read']);
    }
}
