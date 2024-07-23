<?php

namespace App\Controller;

use App\DTO\PaginationDTO;
use App\Entity\Organization;
use App\Entity\OrganizationUser;
use App\Entity\Schedule;
use App\Entity\ScheduleDay;
use App\Entity\User;
use App\Entity\WorkingHour;
use App\Enum\DayEnum;
use App\Enum\OrganizationRoleEnum;
use App\Enum\WorkingDayStatusEnum;
use App\Repository\OrganizationRepository;
use App\Service\EntityService\OrganizationUserService;
use App\Service\EntityService\ScheduleDayService;
use App\Service\EntityService\ScheduleService;
use App\Service\EntityService\WorkingHourService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/organization')]
class OrganizationController extends AbstractController
{
    public function __construct(
        private OrganizationRepository $repository, 
        private EntityManagerInterface $em, 
        private ValidationService $validationService
    )
    {}

    #[Route(name: 'organization.index', methods: ['GET'])]
    public function list(#[MapQueryString] ?PaginationDTO $paginationDTO): JsonResponse
    {
        $organizations = $this->repository->findAllPaginated($paginationDTO?->page ?? 1, $paginationDTO?->limit ?? 10);
        
        return $this->json($organizations, JsonResponse::HTTP_OK, [], ['groups' => [
            'organization.read'
        ]]);
    }

    #[Route('/{id}', name: 'organization.show', methods: ['GET'], requirements: ['id' =>  Requirement::DIGITS])]
    public function show(Organization $organization): JsonResponse
    {
        return $this->json($organization, JsonResponse::HTTP_OK, [], ['groups' => 'organization.read']);
    }

    #[Route(name: 'organization.create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(serializationContext:['groups' => ['organization.create']])] Organization $organization,
        #[CurrentUser] User $user,
        OrganizationUserService $organizationUserService,
        ScheduleService $scheduleService,
        ScheduleDayService $scheduleDayService,
        WorkingHourService $workingHourService
    ): JsonResponse
    {
        $this->em->persist($organization);
        
        $organizationUser = $organizationUserService->createOrganizationUser(
            $organization, 
            $user, 
            OrganizationRoleEnum::ADMIN
        );

        $schedule = $scheduleService->createSchedule($organizationUser);

        // For each day of the week, create a schedule day and a working hour
        foreach (DayEnum::cases() as $day) {
            $workingStatus = in_array($day, [DayEnum::SUNDAY, DayEnum::SATURDAY]) 
            ? WorkingDayStatusEnum::NOT_WORKING 
            : WorkingDayStatusEnum::WORKING;

            $scheduleDay = $scheduleDayService->createScheduleDay($schedule, $day, $workingStatus);

            $workingHourService->createWorkingHour(
                $scheduleDay, 
                new \DateTime('08:00:00'), 
                new \DateTime('17:00:00')
            );
        }

        $this->em->flush();

        return $this->json($organization, JsonResponse::HTTP_CREATED, [], ['groups' => 'organization.read']);
    }

    #[Route('/{id}', name: 'organization.update', methods: ['PUT'], requirements: ['id' =>  Requirement::DIGITS])]
    public function update(Request $request, Organization $organization, SerializerInterface $serializer): JsonResponse
    {
        $serializer->deserialize($request->getContent(), Organization::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $organization,
            'groups' => ['organization.update']
        ]);

        $errors = $this->validationService->validate($organization);
        if ($errors) return $errors;

        $this->em->persist($organization);
        $this->em->flush();

        return $this->json($organization, JsonResponse::HTTP_OK, [], ['groups' => 'organization.read']);
    }

    #[Route('/{id}', name: 'organization.delete', methods: ['DELETE'], requirements: ['id' =>  Requirement::DIGITS])]
    public function delete(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/OrganizationController.php',
        ]);
    }

    #[Route('/{id}/user', name: 'organization.add_user', methods: ['POST'], requirements: ['id' =>  Requirement::DIGITS])]
    public function addUser(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/OrganizationController.php',
        ]);
    }

    #[Route('/{id}/user', name: 'organization.update_user', methods: ['PUT'], requirements: ['id' =>  Requirement::DIGITS])]
    public function updateUser(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/OrganizationController.php',
        ]);
    }

    #[Route('/{id}/user/{user_id}', name: 'organization.remove_user', methods: ['DELETE'], requirements: ['id' =>  Requirement::DIGITS, 'user_id' =>  Requirement::DIGITS])]
    public function removeUser(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/OrganizationController.php',
        ]);
    }
}
