<?php

namespace App\DTO\ScheduleEvent;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as CustomAssert;


class CreateScheduleEventDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\type('\DateTimeInterface')]
        public readonly \DateTimeInterface $start_date,
        #[Assert\NotBlank]
        #[Assert\Length(min: 2, max: 255)]
        public readonly string $firstname,
        #[Assert\NotBlank]
        #[Assert\Length(min: 2, max: 255)]
        public readonly string $lastname,
        #[Assert\NotBlank]
        #[Assert\Email]
        public readonly string $email,
        public readonly string|null $phone,
        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int $schedule_id,
        #[Assert\NotBlank]  
        #[Assert\Positive]
        public readonly int $event_type_id,
        public readonly string|null $address,
        public readonly int|null $postal_code,
        public readonly string|null $city,
        public readonly string|null $country,
    )
    {
    }
}

