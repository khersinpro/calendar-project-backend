<?php

namespace App\DTO\OrganizationUser;

use App\Enum\OrganizationRoleEnum;
use Symfony\Component\Validator\Constraints as Assert;

class CreateOrganizationUserDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int $organization_id,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int $user_id,
        
        #[Assert\NotBlank]
        #[Assert\Choice(callback: [OrganizationRoleEnum::class, 'values'])]
        public readonly string $organization_role,
    )
    {
    }
}