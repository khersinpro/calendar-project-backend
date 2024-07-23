<?php

namespace App\Service\EntityService;

use App\Entity\ScheduleDay;
use App\Entity\WorkingHour;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;

class WorkingHourService
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
    }

    public function createWorkingHour(
        ScheduleDay $scheduleDay, 
        DateTimeInterface $openTime, 
        DateTimeInterface $closeTime,
        bool $persist = true
    ): WorkingHour
    {
        $workingHour = new WorkingHour();
        $workingHour->setOpenTime($openTime);
        $workingHour->setCloseTime($closeTime);
        $workingHour->setScheduleDay($scheduleDay);

        if ($persist) {
            $this->em->persist($workingHour);
        }

        return $workingHour;
    }
}