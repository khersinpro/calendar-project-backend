<?php

namespace App\Service\EntityService;

use App\Entity\OrganizationUser;
use App\Entity\Schedule;
use App\Enum\DayEnum;
use App\Enum\WorkingDayStatusEnum;
use Doctrine\ORM\EntityManagerInterface;

class ScheduleService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ScheduleDayService $scheduleDayService,
        private WorkingHourService $workingHourService
    )
    {
    }

    public function createSchedule(OrganizationUser $organizationUser, bool $persist = true): Schedule
    {
        $schedule = new Schedule();
        $schedule->setOrganizationUser($organizationUser);

        if ($persist) {
            $this->em->persist($schedule);
        }

        return $schedule;
    }

    public function initializeCompleteSchedule(OrganizationUser $organizationUser): void
    {
        $schedule = $this->createSchedule($organizationUser);

        // For each day of the week, create a schedule day and a working hour
        foreach (DayEnum::cases() as $day) {
            $workingStatus = in_array($day, [DayEnum::SUNDAY, DayEnum::SATURDAY]) 
            ? WorkingDayStatusEnum::NOT_WORKING 
            : WorkingDayStatusEnum::WORKING;

            $scheduleDay = $this->scheduleDayService->createScheduleDay($schedule, $day, $workingStatus);

            $this->workingHourService->createWorkingHour(
                $scheduleDay, 
                new \DateTime('08:00:00'), 
                new \DateTime('17:00:00')
            );
        }
    }
}