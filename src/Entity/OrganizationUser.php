<?php

namespace App\Entity;

use App\Enum\OrganizationRoleEnum;
use App\Repository\OrganizationUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;


#[ORM\Entity(repositoryClass: OrganizationUserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_ORGANIZATION_USER', columns: ['organization_id', 'user_id'])]
class OrganizationUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['organization_user.read', 'organization.read'])]
    private ?int $id = null;
    
    #[ORM\ManyToOne(inversedBy: 'organization_users')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['user.read', 'organization.read', 'organization_user.read', 'organization_user.create', 'organization_user.update'])]
    private ?User $user = null;
    
    #[ORM\ManyToOne(inversedBy: 'organization_users')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['organization_user.read', 'organization_user.create', 'organization_user.update'])]
    private ?Organization $organization = null;

    #[ORM\OneToOne(inversedBy: 'organization_user', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['schedule.read', 'organization.read'])]
    private ?Schedule $schedule = null;

    /**
     * @var Collection<int, EventType>
     */
    #[ORM\ManyToMany(targetEntity: EventType::class, mappedBy: 'organization_users')]
    #[Groups(['event_type.read', 'organization.read'])]
    private Collection $event_types;

    #[ORM\Column(enumType: OrganizationRoleEnum::class)]
    #[Groups(['organization_user.read', 'organization.read'])]
    private ?OrganizationRoleEnum $organization_role = null;

    public function __construct()
    {
        $this->event_types = new ArrayCollection();
        $this->organization_role = OrganizationRoleEnum::USER;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): static
    {
        $this->organization = $organization;

        return $this;
    }

    public function getSchedule(): ?Schedule
    {
        return $this->schedule;
    }

    public function setSchedule(Schedule $schedule): static
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * @return Collection<int, EventType>
     */
    public function getEventTypes(): Collection
    {
        return $this->event_types;
    }

    public function addEventType(EventType $event_type): static
    {
        if ($event_type->getOrganization() !== $this->getOrganization()) {
            throw new \Exception('The user oganization must be the same as the event organization');
        }

        if (!$this->event_types->contains($event_type)) {
            $this->event_types->add($event_type);
            $event_type->addOrganizationUser($this);
        }

        return $this;
    }

    public function removeEventType(EventType $event_type): static
    {
        if ($this->event_types->removeElement($event_type)) {
            $event_type->removeOrganizationUser($this);
        }

        return $this;
    }

    public function getOrganizationRole(): ?OrganizationRoleEnum
    {
        return $this->organization_role;
    }

    public function setOrganizationRole(OrganizationRoleEnum $organization_role): static
    {
        $this->organization_role = $organization_role;

        return $this;
    }
}
