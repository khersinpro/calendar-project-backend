<?php

namespace App\DTO\CustomSheduleDay;

use App\Enum\WorkingDayStatusEnum;
use Symfony\Component\Validator\Constraints as Assert;

class CreateCustomSheduleDayDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('\DateTimeInterface')]
        public readonly \DateTimeInterface $date,

        #[Assert\NotBlank]
        #[Assert\Choice(callback: [WorkingDayStatusEnum::class, 'values'])]
        public readonly string $status,
    )
    {
    }
}