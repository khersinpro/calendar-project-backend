<?php

namespace App\Entity;

use App\Repository\PlanningRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanningRepository::class)]
class Planning
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'planning', cascade: ['persist', 'remove'])]
    private ?OrganizationUser $organization_user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrganizationUser(): ?OrganizationUser
    {
        return $this->organization_user;
    }

    public function setOrganizationUser(OrganizationUser $organization_user): static
    {
        // set the owning side of the relation if necessary
        if ($organization_user->getPlanning() !== $this) {
            $organization_user->setPlanning($this);
        }
        
        $this->organization_user = $organization_user;

        return $this;
    }
}
