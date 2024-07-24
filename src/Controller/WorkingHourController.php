<?php

namespace App\Controller;

use App\DTO\PaginationDTO;
use App\DTO\WorkingHour\createWorkingHourDTO;
use App\DTO\WorkingHour\updateWorkingHourDTO;
use App\Entity\ScheduleDay;
use App\Entity\WorkingHour;
use App\Repository\WorkingHourRepository;
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
        ScheduleDay $scheduleDay
    )
    {
        $currentWorkingHour = $scheduleDay->getWorkingHours();
        $newOpenTime = $data->open_time;
        $newCloseTime = $data->close_time;
        $midnightCloseTime = $newCloseTime === '00:00:00';

        if (!$midnightCloseTime && $newOpenTime > $newCloseTime) {
            return $this->json(['error' => 'The working hours overlap with existingz hours.'], JsonResponse::HTTP_BAD_REQUEST);
        }
    
        foreach($currentWorkingHour as $workingHour) {
            $currentOpenTime = $workingHour->getOpenTime()->format('H:i:s');
            $currentCloseTime = $workingHour->getCloseTime()->format('H:i:s');

            $currentCloseTimeMidnight = $currentCloseTime === '00:00:00';

            if ($midnightCloseTime && $currentCloseTimeMidnight) {
                return $this->json(['error' => 'The working hours overlap with existing hours.'], JsonResponse::HTTP_BAD_REQUEST);
            }
            
            if ($midnightCloseTime && 
            ($newOpenTime > $currentOpenTime && $newOpenTime >= $currentCloseTime)
            ) {
                var_dump("shit", $currentOpenTime, $currentCloseTime);
                continue;
            }

            if ($midnightCloseTime &&
                ($newOpenTime < $currentCloseTime)
            ) {
                var_dump("shit3", $currentOpenTime, $currentCloseTime);
                return $this->json(['error' => 'The working hours overlap with existing hours.'], JsonResponse::HTTP_BAD_REQUEST);
                continue;
            }


            if (
                $currentCloseTimeMidnight && 
                ($newOpenTime >= $currentOpenTime) ||
                ($newCloseTime > $currentOpenTime && $newCloseTime <= $currentCloseTime)
            )
            {
                return $this->json(['error' => 'The working hours overlap with existing ss hours.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            if ($currentCloseTimeMidnight && $newOpenTime === '00:00:00' && $newCloseTime <= $currentOpenTime) {
                continue;
            }

            // if new open time is between current open time and current close time
            // or new close time is between current open time and current close time
            // or new open time is before current close time and new close time is after current open time
            if (
                ($newOpenTime >= $currentOpenTime && $newOpenTime < $currentCloseTime) || 
                ($newCloseTime > $currentOpenTime && ($newCloseTime <= $currentCloseTime && $newCloseTime)) ||
                ($newOpenTime <= $currentOpenTime && $newCloseTime >= $currentCloseTime) ||
                ($currentCloseTime === '00:00:00'  && $currentOpenTime === '00:00:00')
            ) {
                return $this->json(['error' => 'The working hours overlap with existing  icihours.'], JsonResponse::HTTP_BAD_REQUEST);
            }
 
        }

        $workingHour = new WorkingHour();
        $workingHour->setOpenTime(new \DateTime($data->open_time));
        $workingHour->setCloseTime(new \DateTime($data->close_time));
        $workingHour->setScheduleDay($scheduleDay);

        $this->em->persist($workingHour);
        $this->em->flush();

        return $this->json($workingHour, JsonResponse::HTTP_CREATED, [], ['groups' => 'working_hour.read']);
    }

    #[Route('/{id}', name: 'working_hour.update', methods: ['PUT'], requirements: ['id' =>  Requirement::DIGITS])]
    public function update(
        #[MapRequestPayload] createWorkingHourDTO $data,
        WorkingHour $workingHour
    )
    {
        // dd($workingHour->getOpenTime());

        $scheduleDay = $workingHour->getScheduleDay();

        if (!$scheduleDay) {
            return $this->json('The working hour must be associated to a schedule day', JsonResponse::HTTP_BAD_REQUEST);
        }

        $currentWorkingHours = $scheduleDay->getWorkingHours();

        foreach($currentWorkingHours as $currentWorkingHour) {
            if ($workingHour->getId() === $currentWorkingHour->getId()) {
                continue;
            }

            $currentOpenTime = $currentWorkingHour->getOpenTime()->format('H:i');
            $currentCloseTime = $currentWorkingHour->getCloseTime()->format('H:i');
            dd($currentOpenTime, $currentCloseTime);
            // if new open time is between current open time and current close time
            // or new close time is between current open time and current close time
            // or new open time is before current close time and new close time is after current open time
            if (
                ($data->open_time >= $currentOpenTime && $data->open_time < $currentCloseTime) || 
                ($data->close_time > $currentOpenTime && $data->close_time <= $currentCloseTime) ||
                ($data->open_time <= $currentOpenTime && $data->close_time >= $currentCloseTime)
            ) {
                return $this->json(['error' => 'The working hours overlap with existing hours.'], JsonResponse::HTTP_BAD_REQUEST);
            }

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
