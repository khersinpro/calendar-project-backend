<?php

namespace App\Controller;

use App\DTO\PaginationDTO;
use App\DTO\ScheduleDay\UpdateScheduleDayDTO;
use App\DTO\WorkingHour\createWorkingHourDTO;
use App\Entity\CustomScheduleDay;
use App\Entity\CustomWorkingHour;
use App\Entity\Schedule;
use App\Enum\WorkingDayStatusEnum;
use App\Repository\CustomScheduleDayRepository;
use App\Repository\CustomWorkingHourRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api/custom-schedule-day')]
class CustomScheduleDayController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {}

    #[Route(name: 'custom_schedule_day.list', methods: ['GET'])]
    public function list(
        CustomScheduleDayRepository $customSheduleDayRepository,
        #[MapQueryString] ?PaginationDTO $paginationDTO
    ): JsonResponse
    {
        $customSheduleDays = $customSheduleDayRepository->findAllPaginated($paginationDTO?->page ?? 1, $paginationDTO?->limit ?? 10);
        return $this->json($customSheduleDays, JsonResponse::HTTP_OK, [], ['groups' => 'custom_schedule_day.read']);
    }

    #[Route('/{id}', name: 'custom_schedule_day.show', methods: ['GET'], requirements: ['id' =>  Requirement::DIGITS])]
    public function show(CustomScheduleDay $customScheduleDay): JsonResponse
    {
        return $this->json($customScheduleDay, JsonResponse::HTTP_OK, [], ['groups' => 'custom_schedule_day.read']);
    }

    #[Route('/schedule-{id)', name: 'custom_schedule_day.create', methods: ['POST'], requirements: ['id' =>  Requirement::DIGITS])] 
    public function create(
        #[MapRequestPayload] CustomScheduleDay $customScheduleDay,
        Schedule $schedule,
        CustomScheduleDayRepository $customSheduleDayRepository
    ): JsonResponse
    {
        $existingCustomScheduleDay = $customSheduleDayRepository->findOneBy([
            'shedule_id' => $schedule->getId(),
            'date' => $customScheduleDay->getDate()
        ]);

        if ($existingCustomScheduleDay) {
            return $this->json('The custom schedule day already exists', JsonResponse::HTTP_BAD_REQUEST);
        }

        $customScheduleDay->setSchedule($schedule);
        $this->em->persist($customScheduleDay);
        $this->em->flush();

        return $this->json($customScheduleDay, JsonResponse::HTTP_CREATED, [], ['groups' => 'custom_schedule_day.read']);
    }
        
    #[Route('/{id}', name: 'custom_schedule_day.update', methods: ['PUT'], requirements: ['id' =>  Requirement::DIGITS])]
    public function update(
        #[MapRequestPayload] UpdateScheduleDayDTO $data,
        CustomScheduleDay $customScheduleDay
    ): JsonResponse
    {
        $customScheduleDay->setStatus(WorkingDayStatusEnum::from($data->status));
        $this->em->persist($customScheduleDay);
        $this->em->flush();
        return $this->json($customScheduleDay, JsonResponse::HTTP_OK, [], ['groups' => 'custom_schedule_day.read']);
    }

    #[Route('/{id}', name: 'custom_schedule_day.delete', methods: ['DELETE'], requirements: ['id' =>  Requirement::DIGITS])]    
    public function delete(CustomScheduleDay $customScheduleDay): JsonResponse
    {
        $this->em->remove($customScheduleDay);
        $this->em->flush();

        return $this->json('', JsonResponse::HTTP_NO_CONTENT);
    }
}
