<?php

namespace App\Controller;

use App\DTO\OrganizationUser\CreateOrganizationUserDTO;
use App\DTO\OrganizationUser\UpdateOrganizationUserDTO;
use App\Entity\OrganizationUser;
use App\Enum\OrganizationRoleEnum;
use App\Repository\OrganizationRepository;
use App\Repository\OrganizationUserRepository;
use App\Repository\UserRepository;
use App\Service\EntityService\OrganizationUserService;
use App\Service\EntityService\ScheduleService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api/organization-user')]
class OrganizationUserController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {}

    #[Route(name: 'organization_user.list', methods: ['GET'])]
    public function list(OrganizationUserRepository $organizationUserRepository): JsonResponse
    {
        $organizationUsers = $organizationUserRepository->findAllPaginated(1, 10);

        return $this->json($organizationUsers, JsonResponse::HTTP_OK, [], ['groups' => 'organization_user.read']);
    }

    #[Route('/{id}', name: 'organization_user.show', methods: ['GET'], requirements: ['id' =>  Requirement::DIGITS])]
    public function show(OrganizationUser $organizationUser): JsonResponse
    {
        return $this->json($organizationUser, JsonResponse::HTTP_OK, [], ['groups' => 'organization_user.read']);
    }

    #[Route(name: 'organization_user.create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CreateOrganizationUserDTO $data,
        UserRepository $userRepository,
        OrganizationRepository $organizationRepository,
        ScheduleService $scheduleService,
        OrganizationUserService $organizationUserService
    ): JsonResponse
    {
        $user = $userRepository->findOneBy(['id' => $data->user_id]);

        if (!$user) {
            return $this->json('User not found', JsonResponse::HTTP_NOT_FOUND);
        }

        $organisation = $organizationRepository->findOneBy(['id' => $data->organization_id]);

        if (!$organisation) {
            return $this->json('Organization not found', JsonResponse::HTTP_NOT_FOUND);
        }
    
        $organizationUser = $organizationUserService->createOrganizationUser(
            $organisation, 
            $user, 
            OrganizationRoleEnum::from($data->organization_role)
        );

        $scheduleService->initializeCompleteSchedule($organizationUser);

        $this->em->flush();
        return $this->json($organizationUser, JsonResponse::HTTP_CREATED, [], ['groups' => 'organization_user.read']);
    }

    #[Route('/{id}', name: 'organization_user.update', methods: ['PUT'], requirements: ['id' =>  Requirement::DIGITS])]
    public function update(
        #[MapRequestPayload] UpdateOrganizationUserDTO $data,
        OrganizationUser $organizationUser, 
        ValidationService $validationService
    ): JsonResponse
    {
        $organizationUser->setOrganizationRole(OrganizationRoleEnum::from($data->organization_role));

        $errors = $validationService->validate($organizationUser);
        if ($errors) return $errors;

        $this->em->persist($organizationUser);
        $this->em->flush();

        return $this->json($organizationUser, JsonResponse::HTTP_OK, [], ['groups' => 'organization_user.read']);
    }

    #[Route('/{id}', name: 'organization_user.delete', methods: ['DELETE'], requirements: ['id' =>  Requirement::DIGITS])]
    public function delete(OrganizationUser $organizationUser): JsonResponse
    {
        $this->em->remove($organizationUser);
        $this->em->flush();

        return $this->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
