<?php

namespace App\Controller;

use App\DTO\CustomSheduleDay\CreateCustomSheduleDayDTO;
use App\DTO\PaginationDTO;
use App\DTO\ScheduleDay\UpdateScheduleDayDTO;
use App\Entity\CustomScheduleDay;
use App\Entity\Schedule;
use App\Enum\WorkingDayStatusEnum;
use App\Repository\CustomScheduleDayRepository;
use App\Service\EntityService\CustomWorkingHourService;
use App\Service\ValidationService;
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

    #[Route('/{id}', name: 'custom_schedule_day.create', methods: ['POST'], requirements: ['id' =>  Requirement::DIGITS])]
    public function create(
        #[MapRequestPayload] CreateCustomSheduleDayDTO $data,
        Schedule $schedule,
        CustomScheduleDayRepository $customSheduleDayRepository,
        ValidationService $validationService,
        CustomWorkingHourService $customWorkingHourService
    ): JsonResponse
    {
        $existingCustomScheduleDay = $customSheduleDayRepository->findOneBy([
            'schedule' => $schedule->getId(),
            'date' => $data->date
        ]);

        if ($existingCustomScheduleDay) {
            return $this->json('The custom schedule day already exists', JsonResponse::HTTP_BAD_REQUEST);
        }

        $customScheduleDay = new CustomScheduleDay();
        $customScheduleDay->setDate($data->date);
        $customScheduleDay->setStatus(WorkingDayStatusEnum::from($data->status));
        $customScheduleDay->setSchedule($schedule);

        if ($data->status === WorkingDayStatusEnum::WORKING) {
            $customWorkingHourService->create(
                new \DateTime('08:00:00'), 
                new \DateTime('17:00:00'),
                $customScheduleDay
            );
        }

        $error = $validationService->validate($customScheduleDay); 
        if ($error) return $error;

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
