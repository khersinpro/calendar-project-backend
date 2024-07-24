<?php

namespace App\Controller;

use App\DTO\PaginationDTO;
use App\Entity\Schedule;
use App\Repository\ScheduleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api/schedule')]
class ScheduleController extends AbstractController
{
    #[Route(name: 'app_schedule')]
    public function index(
        ScheduleRepository $scheduleRepository,
        #[MapQueryString] ?PaginationDTO  $paginationDTO
    ): JsonResponse
    {
        $schedules = $scheduleRepository->findAllPaginated($paginationDTO?->page ?? 1, $paginationDTO?->limit ?? 10);

        return $this->json($schedules, JsonResponse::HTTP_OK, [], ['groups' => 'schedule.read']);
    }

    #[Route('/{id}', name: 'app_schedule_show', methods: ['GET'], requirements: ['id' =>  Requirement::DIGITS])]
    public function show(Schedule $schedule): JsonResponse
    {
        return $this->json($schedule, JsonResponse::HTTP_OK, [], ['groups' => 'schedule.read']);
    }
}
