<?php

namespace App\Service\Utils;

use App\Entity\WorkingHour;
use DateTime;

class TimeService {
    /**
     * Transform an hour and minutes into minutes
     * Exemple: 14:15 -> 870
     * @param DateTime $time - the time to transform into minutes
     * @return int - the transformed time
     */
    public static function toMin(DateTime $time) {
        $time = $time->format('H:i');

        list($hours, $minutes) = explode(':', $time);
        return intval($hours) * 60 + intval($minutes);
    }

    /**
     * Check if a time slot is valid with a list of working hours or custom working hours
     * @param WorkingHour[]|CustomWorkingHour[] $workingHours - the list of working hours or custom working hours
     * @param DateTime $newOpenTime - the new open time
     * @param DateTime $newCloseTime - the new close time
     * @return bool - true if the time slot is valid, false otherwise
     */
    public static function isTimeSlotValid($workingHours, DateTime $newOpenTime, DateTime $newCloseTime): bool
    {
        foreach ($workingHours as $workingHour) {
            $openTime = self::toMin($workingHour->getOpenTime());
            $closeTime = self::toMin($workingHour->getCloseTime());
            $newOpen = self::toMin($newOpenTime);
            $newClose = self::toMin($newCloseTime);

            // New close time is the same as new open time
            if ($newClose === $newOpen) {
                return false;
            }
            
            // new close time is not midnight and new open time is greater than new close time
            if($newCloseTime->format('H:i') !== '00:00' && $newOpen > $newClose) {
                return false;
            }

            // Close time is midnight for both close hours
            if ($workingHour->getCloseTime()->format('H:i') === '00:00' && $newCloseTime->format('H:i') === '00:00') {
                return false; 
            }

            // Close time is midnight for new and close time is greater than new open time
            if ($newCloseTime->format('H:i') === '00:00' && $closeTime > $newOpen) {
                return false;
            }

            if ($closeTime === 0 && $newClose > $openTime) {
                return false;
            }

            // New open time is between close time and new close time
            if ($newOpen < $closeTime && $newClose > $openTime) {
                return false; 
            }
        }

        return true;
    }
}

