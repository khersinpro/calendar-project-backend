<?php

namespace App\DTO\WorkingHour;

use Symfony\Component\Validator\Constraints as Assert;

class createWorkingHourDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Time]
        public readonly string $open_time,
        
        #[Assert\NotBlank]
        #[Assert\Time]
        public readonly string $close_time,
    )
    {
    }
}