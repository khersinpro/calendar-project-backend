<?php

namespace App\Entity;

use App\Repository\OrganizationUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrganizationUserRepository::class)]
class OrganizationUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'organization_users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'organization_users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Organization $organization = null;

    #[ORM\OneToOne(inversedBy: 'organization_user', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Planning $planning = null;

    /**
     * @var Collection<int, EventType>
     */
    #[ORM\ManyToMany(targetEntity: EventType::class, mappedBy: 'organization_users')]
    private Collection $event_types;

    public function __construct()
    {
        $this->event_types = new ArrayCollection();
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

    public function getPlanning(): ?Planning
    {
        return $this->planning;
    }

    public function setPlanning(Planning $planning): static
    {
        $this->planning = $planning;

        return $this;
    }

    /**
     * @return Collection<int, EventType>
     */
    public function getEventTypes(): Collection
    {
        return $this->event_types;
    }

    public function addEventType(EventType $eventType): static
    {
        if ($eventType->getOrganization() !== $this->getOrganization()) {
            throw new \Exception('The user oganization must be the same as the event organization');
        }

        if (!$this->event_types->contains($eventType)) {
            $this->event_types->add($eventType);
            $eventType->addOrganizationUser($this);
        }

        return $this;
    }

    public function removeEventType(EventType $eventType): static
    {
        if ($this->event_types->removeElement($eventType)) {
            $eventType->removeOrganizationUser($this);
        }

        return $this;
    }
}
