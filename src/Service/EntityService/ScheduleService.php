<?php

namespace App\Service\EntityService;

use App\Entity\OrganizationUser;
use App\Entity\Schedule;
use Doctrine\ORM\EntityManagerInterface;

class ScheduleService
{
    public function __construct(
        private EntityManagerInterface $em
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
}