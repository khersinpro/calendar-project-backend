<?php

namespace App\DTO\WorkingHour;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as CustomValidator;
 
class createWorkingHourDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Time]
        #[CustomValidator\ValidWorkingHour]
        public readonly string $open_time,
        
        #[Assert\NotBlank]
        #[Assert\Time]
        #[CustomValidator\ValidWorkingHour]
        public readonly string $close_time,
    )
    {
    }
}