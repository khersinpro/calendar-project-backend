<?php

namespace App\Entity;

use App\Repository\EventTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventTypeRepository::class)]
class EventType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $price = null;

    #[ORM\Column]
    private ?bool $payment_required = null;

    #[ORM\Column]
    private ?bool $deposit_required = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $deposit_amout = null;

    #[ORM\Column]
    private ?bool $address_required = null;

    #[ORM\ManyToOne(inversedBy: 'eventTypes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Organization $organization = null;

    /**
     * @var Collection<int, OrganizationUser>
     */
    #[ORM\ManyToMany(targetEntity: OrganizationUser::class, inversedBy: 'event_types')]
    private Collection $organization_users;

    /**
     * @var Collection<int, PlanningEvent>
     */
    #[ORM\OneToMany(targetEntity: PlanningEvent::class, mappedBy: 'event_type')]
    private Collection $planningEvents;

    public function __construct()
    {
        $this->payment_required = false;
        $this->deposit_required = false;
        $this->address_required = false;
        $this->organization_users = new ArrayCollection();
        $this->planningEvents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isPaymentRequired(): ?bool
    {
        return $this->payment_required;
    }

    public function setPaymentRequired(bool $payment_required): static
    {
        $this->payment_required = $payment_required;

        return $this;
    }

    public function isDepositRequired(): ?bool
    {
        return $this->deposit_required;
    }

    public function setDepositRequired(bool $deposit_required): static
    {
        $this->deposit_required = $deposit_required;

        return $this;
    }

    public function getDepositAmout(): ?string
    {
        return $this->deposit_amout;
    }

    public function setDepositAmout(string $deposit_amout): static
    {
        $this->deposit_amout = $deposit_amout;

        return $this;
    }

    public function isAddressRequired(): ?bool
    {
        return $this->address_required;
    }

    public function setAddressRequired(bool $address_required): static
    {
        $this->address_required = $address_required;

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

    /**
     * @return Collection<int, OrganizationUser>
     */
    public function getOrganizationUsers(): Collection
    {
        return $this->organization_users;
    }

    public function addOrganizationUser(OrganizationUser $organizationUser): static
    {
        if ($organizationUser->getOrganization() !== $this->getOrganization()) {
            throw new \Exception('The user oganization must be the same as the event organization');
        }
        
        if (!$this->organization_users->contains($organizationUser)) {
            $this->organization_users->add($organizationUser);
        }

        return $this;
    }

    public function removeOrganizationUser(OrganizationUser $organizationUser): static
    {
        $this->organization_users->removeElement($organizationUser);

        return $this;
    }

    /**
     * @return Collection<int, PlanningEvent>
     */
    public function getPlanningEvents(): Collection
    {
        return $this->planningEvents;
    }

    public function addPlanningEvent(PlanningEvent $planningEvent): static
    {
        if (!$this->planningEvents->contains($planningEvent)) {
            $this->planningEvents->add($planningEvent);
            $planningEvent->setEventType($this);
        }

        return $this;
    }

    public function removePlanningEvent(PlanningEvent $planningEvent): static
    {
        if ($this->planningEvents->removeElement($planningEvent)) {
            // set the owning side to null (unless already changed)
            if ($planningEvent->getEventType() === $this) {
                $planningEvent->setEventType(null);
            }
        }

        return $this;
    }
}
