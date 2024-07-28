<?php

namespace App\Controller;

use App\DTO\PaginationDTO;
use App\DTO\WorkingHour\createWorkingHourDTO;
use App\Entity\CustomScheduleDay;
use App\Entity\CustomWorkingHour;
use App\Repository\CustomWorkingHourRepository;
use App\Service\EntityService\CustomWorkingHourService;
use App\Service\Utils\TimeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api/custom-working-hour')]
class CustomWorkingHourController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {}
    #[Route(name: 'custom_working_hour.list', methods: ['GET'])]
    public function list(
        CustomWorkingHourRepository $customWorkingHourRepository, 
        #[MapQueryString] ?PaginationDTO $paginationDTO
    ): JsonResponse
    {
        $customWorkingHours = $customWorkingHourRepository->findAllPaginated($paginationDTO?->page ?? 1, $paginationDTO?->limit ?? 10);
        return $this->json($customWorkingHours, JsonResponse::HTTP_OK, [], ['groups' => 'custom_working_hour.read']);
    }

    #[Route('/{id}', name: 'custom_working_hour.show', methods: ['GET'], requirements: ['id' =>  Requirement::DIGITS])]
    public function show(CustomWorkingHour $customWorkingHour): JsonResponse
    {
        return $this->json($customWorkingHour, JsonResponse::HTTP_OK, [], ['groups' => 'custom_working_hour.read']);
    }

    #[Route('/{id}', name: 'custom_working_hour.create', methods: ['POST'], requirements: ['id' =>  Requirement::DIGITS])]
    public function create(
        #[MapRequestPayload] createWorkingHourDTO $data,
        CustomScheduleDay $customScheduleDay,
        CustomWorkingHourService $customWorkingHourService
    )
    {
        $currentWorkingHour = $customScheduleDay->getCustomWorkingHours();
        $newOpenTime = new \DateTime($data->open_time);
        $newCloseTime = new \DateTime($data->close_time);

        $timeSlotValid = TimeService::isTimeSlotValid(
            $currentWorkingHour, 
            $newOpenTime, 
            $newCloseTime
        );

        if (!$timeSlotValid) {
            return $this->json([
                'error' => 'The working hours overlap with existing hours.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $workingHour = $customWorkingHourService->create(
            new \DateTime($data->open_time), 
            new \DateTime($data->close_time),
            $customScheduleDay
        );

        $this->em->flush();

        return $this->json($workingHour, JsonResponse::HTTP_CREATED, [], ['groups' => 'custom_working_hour.read']);
    }

    #[Route('/{id}', name: 'custom_working_hour.update', methods: ['PUT'], requirements: ['id' =>  Requirement::DIGITS])]
    public function update(
        #[MapRequestPayload] createWorkingHourDTO $data,
        CustomWorkingHour $customWorkingHour
    )
    {
        $customScheduleDay = $customWorkingHour->getCustomScheduleDay();

        if (!$customScheduleDay) {
            return $this->json('The working hour must be associated to a custom schedule day', JsonResponse::HTTP_BAD_REQUEST);
        }

        $currentWorkingHours = $customScheduleDay->getCustomWorkingHours()->filter(fn($currentWorkingHour) => $currentWorkingHour->getId() !== $customWorkingHour->getId());

        $customWorkingHour->setOpenTime(new \DateTime($data->open_time));
        $customWorkingHour->setCloseTime(new \DateTime($data->close_time));

        $timeSlotValid = TimeService::isTimeSlotValid(
            $currentWorkingHours, 
            $customWorkingHour->getOpenTime(), 
            $customWorkingHour->getCloseTime()
        );

        if (!$timeSlotValid) {
            return $this->json(['error' => 'The working hours overlap with existing hours.'], JsonResponse::HTTP_BAD_REQUEST);  
        }

        $this->em->persist($customWorkingHour);
        $this->em->flush();

        return $this->json($customWorkingHour, JsonResponse::HTTP_OK, [], ['groups' => 'custom_working_hour.read']);
    }

    #[Route('/{id}', name: 'custom_working_hour.delete', methods: ['DELETE'], requirements: ['id' =>  Requirement::DIGITS])]
    public function delete(CustomWorkingHour $customWorkingHour): JsonResponse
    {
        $this->em->remove($customWorkingHour);
        $this->em->flush();

        return $this->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
