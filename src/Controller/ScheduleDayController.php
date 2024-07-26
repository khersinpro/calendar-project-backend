<?php

namespace App\Controller;

use App\DTO\PaginationDTO;
use App\DTO\ScheduleDay\UpdateScheduleDayDTO;
use App\Entity\ScheduleDay;
use App\Enum\WorkingDayStatusEnum;
use App\Repository\ScheduleDayRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api/schedule-day')]
class ScheduleDayController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {}

    #[Route(name: 'schedule_day.list', methods: ['GET'])]
    public function list(
        ScheduleDayRepository $scheduleDayRepository,
        #[MapQueryString] ?PaginationDTO $paginationDTO
    ): JsonResponse
    {
        $scheduleDays = $scheduleDayRepository->findAllPaginated($paginationDTO?->page ?? 1, $paginationDTO?->limit ?? 10);

        return $this->json($scheduleDays, JsonResponse::HTTP_OK, [], ['groups' => 'schedule_day.read']);
    }

    #[Route('/{id}', name: 'schedule_day.show', methods: ['GET'], requirements: ['id' =>  Requirement::DIGITS])]
    public function show(ScheduleDay $scheduleDay): JsonResponse
    {
        return $this->json($scheduleDay, JsonResponse::HTTP_OK, [], ['groups' => 'schedule_day.read']);
    }

    #[Route('/{id}', name: 'schedule_day.update', methods: ['PUT'], requirements: ['id' =>  Requirement::DIGITS])]
    public function update(
        #[MapRequestPayload] UpdateScheduleDayDTO $data,
        ScheduleDay $scheduleDay
    ): JsonResponse
    {
        $scheduleDay->setStatus(WorkingDayStatusEnum::from($data->status));
        $this->em->persist($scheduleDay);
        $this->em->flush();
        return $this->json($scheduleDay, JsonResponse::HTTP_OK, [], ['groups' => 'schedule_day.read']);
    }
}
