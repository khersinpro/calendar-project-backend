<?php

namespace App\Controller;

use App\DTO\PaginationDTO;
use App\DTO\WorkingHour\createWorkingHourDTO;
use App\DTO\WorkingHour\updateWorkingHourDTO;
use App\Entity\ScheduleDay;
use App\Entity\WorkingHour;
use App\Repository\WorkingHourRepository;
use App\Service\EntityService\WorkingHourService;
use App\Service\Utils\TimeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api/working-hour')]
class WorkingHourController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {}

    #[Route(name: 'working_hour.list', methods: ['GET'])]
    public function list(
        WorkingHourRepository $workingHourRepository,
        #[MapQueryString] ?PaginationDTO $paginationDTO,
    ): JsonResponse
    {
        $workingHours = $workingHourRepository->findAllPaginated($paginationDTO?->page ?? 1, $paginationDTO?->limit ?? 10);

        return $this->json($workingHours, JsonResponse::HTTP_OK, [], ['groups' => 'working_hour.read']);
    }
     

    #[Route('/{id}', name: 'working_hour.show', methods: ['GET'], requirements: ['id' =>  Requirement::DIGITS])]
    public function show(WorkingHour $workingHour): JsonResponse
    {
        return $this->json($workingHour, JsonResponse::HTTP_OK, [], ['groups' => 'working_hour.read']);
    }

    #[Route('/{id}', name: 'working_hour.create', methods: ['POST'], requirements: ['id' =>  Requirement::DIGITS])]
    public function create(
        #[MapRequestPayload] createWorkingHourDTO $data,
        ScheduleDay $scheduleDay,
        WorkingHourService $workingHourService
    )
    {
        $currentWorkingHour = $scheduleDay->getWorkingHours();
        $newOpenTime = new \DateTime($data->open_time);
        $newCloseTime = new \DateTime($data->close_time);

        $timeSlotValid = TimeService::isTimeSlotValid(
            $currentWorkingHour, 
            $newOpenTime, 
            $newCloseTime
        );

        if (!$timeSlotValid) {
            return $this->json(['error' => 'The working hours overlap with existing hours.'], JsonResponse::HTTP_BAD_REQUEST);
        }


        $workingHour =$workingHourService->createWorkingHour(
            $scheduleDay, 
            $newOpenTime, 
            $newCloseTime
        );
     
        $this->em->flush();

        return $this->json($workingHour, JsonResponse::HTTP_CREATED, [], ['groups' => 'working_hour.read']);
    }

    #[Route('/{id}', name: 'working_hour.update', methods: ['PUT'], requirements: ['id' =>  Requirement::DIGITS])]
    public function update(
        #[MapRequestPayload] createWorkingHourDTO $data,
        WorkingHour $workingHour
    )
    {
        $scheduleDay = $workingHour->getScheduleDay();

        if (!$scheduleDay) {
            return $this->json('The working hour must be associated to a schedule day', JsonResponse::HTTP_BAD_REQUEST);
        }

        $currentWorkingHours = $scheduleDay->getWorkingHours()->filter(fn($currentWorkingHour) => $currentWorkingHour->getId() !== $workingHour->getId());

        $workingHour->setOpenTime(new \DateTime($data->open_time));
        $workingHour->setCloseTime(new \DateTime($data->close_time));

        $timeSlotValid = TimeService::isTimeSlotValid(
            $currentWorkingHours, 
            $workingHour->getOpenTime(), 
            $workingHour->getCloseTime()
        );

        if (!$timeSlotValid) {
            return $this->json(['error' => 'The working hours overlap with existing hours.'], JsonResponse::HTTP_BAD_REQUEST);  
        }

        $this->em->persist($workingHour);
        $this->em->flush();

        return $this->json($workingHour, JsonResponse::HTTP_OK, [], ['groups' => 'working_hour.read']);
    }

    #[Route('/{id}', name: 'working_hour.delete', methods: ['DELETE'], requirements: ['id' =>  Requirement::DIGITS])]
    public function delete(WorkingHour $workingHour): JsonResponse
    {
        $this->em->remove($workingHour);
        $this->em->flush();

        return $this->json('', JsonResponse::HTTP_NO_CONTENT);
    }   
}
