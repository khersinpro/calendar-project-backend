<?php

namespace App\Service\EntityService;

use App\Entity\Organization;
use App\Entity\OrganizationUser;
use App\Entity\User;
use App\Enum\OrganizationRoleEnum;
use Doctrine\ORM\EntityManagerInterface;

class OrganizationUserService
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
    }

    public function createOrganizationUser(
        Organization $organization, 
        User $user, 
        OrganizationRoleEnum $role, 
        bool $persist = true
    ): OrganizationUser
    {
        $organizationUser = new OrganizationUser();
        $organizationUser->setOrganization($organization);
        $organizationUser->setUser($user);
        $organizationUser->setOrganizationRole($role);

        if ($persist) {
            $this->em->persist($organizationUser);
        }

        return $organizationUser;
    }
}