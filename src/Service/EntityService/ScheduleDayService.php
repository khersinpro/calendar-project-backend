<?php

namespace App\Service\EntityService;

use App\Entity\Schedule;
use App\Entity\ScheduleDay;
use App\Enum\DayEnum;
use App\Enum\WorkingDayStatusEnum;
use Doctrine\ORM\EntityManagerInterface;

class ScheduleDayService
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
    }

    public function createScheduleDay(
        Schedule $schedule, 
        DayEnum $day, 
        WorkingDayStatusEnum $status,
        bool $persist = true
    ): ScheduleDay
    {
        $scheduleDay = new ScheduleDay();
        $scheduleDay->setDayOfWeek($day);
        $scheduleDay->setStatus($status);
        $scheduleDay->setSchedule($schedule);

        if ($persist) {
            $this->em->persist($scheduleDay);
        }

        return $scheduleDay;
    }
}