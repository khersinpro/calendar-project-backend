<?php

namespace App\DTO\ScheduleDay;

use App\Enum\WorkingDayStatusEnum;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateScheduleDayDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(callback: [WorkingDayStatusEnum::class, 'values'])]
        public readonly string $status,
    )
    {
    }
}