<?php

namespace App\Service\EntityService;

use App\Entity\CustomScheduleDay;
use App\Entity\CustomWorkingHour;
use Doctrine\ORM\EntityManagerInterface;

class CustomWorkingHourService
{
    public function __construct(
        private EntityManagerInterface $em  
    )
    {
    }

    /**
     * Create a custom working hour
     * @param \DateTime $openTime - the open time
     * @param \DateTime $closeTime - the close time
     * @param CustomScheduleDay $customScheduleDay - the custom schedule day
     * @param bool $persist - whether to persist the entity or not
     * @return CustomWorkingHour - the created custom working hour
     */
    public function create(
        \DateTime $openTime, 
        \DateTime $closeTime,
        CustomScheduleDay $customScheduleDay,
        bool $persist = true
    ): CustomWorkingHour
    {
        $customWorkingHour = new CustomWorkingHour();
        $customWorkingHour->setOpenTime($openTime);
        $customWorkingHour->setCloseTime($closeTime);
        $customWorkingHour->setCustomScheduleDay($customScheduleDay);

        if ($persist) {
            $this->em->persist($customWorkingHour);
        }

        return $customWorkingHour;
    }
}