<?php

namespace App\DTO\WorkingHour;

use Symfony\Component\Validator\Constraints as Assert;

class updateWorkingHourDTO
{
    public function __construct(
        #[Assert\Time]
        public readonly ?string $open_time = null,
        
        #[Assert\Time]
        public readonly ?string $close_time = null,
    )
    {
    }
}