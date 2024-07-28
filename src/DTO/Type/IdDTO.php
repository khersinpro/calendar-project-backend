<?php

namespace App\DTO\Type;

use Symfony\Component\Validator\Constraints as Assert;

class IdDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int $id,
    )
    {
    }
}