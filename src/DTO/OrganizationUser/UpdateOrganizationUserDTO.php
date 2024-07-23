<?php

namespace App\DTO\OrganizationUser;

use App\Enum\OrganizationRoleEnum;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateOrganizationUserDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(callback: [OrganizationRoleEnum::class, 'values'])]
        public readonly string $organization_role,
    )
    {
    }
}