<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/user')]
class UserController extends AbstractController
{
    public function __construct(private UserRepository $repository, private ValidationService $validationService, private EntityManagerInterface $em)       
    {}

    #[Route(name: 'user.list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $user = $this->repository->findAll();

        return $this->json($user, JsonResponse::HTTP_OK, [], ['groups' => 'user.read']);
    }

    #[Route('/{id}', name: 'user.show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(User $user): JsonResponse
    {
        return $this->json($user, JsonResponse::HTTP_OK, [], ['groups' => 'user.read']);
    }

    #[Route(name: 'user.create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(
            serializationContext: ['groups' => ['user.create']]
        )] User $user
    ): JsonResponse
    {
        $this->em->persist($user);
        $this->em->flush();
        return $this->json($user, JsonResponse::HTTP_CREATED, [], ['groups' => 'user.read']);
    }

    #[Route('/{id}', name: 'user.update', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function update(Request $request, User $user, SerializerInterface $serializer): JsonResponse
    {   
        $serializer->deserialize ($request->getContent(), User::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $user,
            'groups' => ['user.update'] 
        ]);

        $error = $this->validationService->validate($user);
        if ($error) return $error;
        
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

}
