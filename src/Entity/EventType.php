<?php

namespace App\Entity;

use App\Enum\EventPaymentConditionEnum;
use App\Repository\EventTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventTypeRepository::class)]
class EventType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['event_type.read', 'organization.read'])]
    private ?int $id = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2, 
        max: 255, 
        minMessage: 'The name must be at least 2 characters', 
        maxMessage: 'The name cannot be longer than 255 characters'
    )]
    #[Groups(['event_type.read', 'event_type.create', 'event_type.update', 'organization.read'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Groups(['event_type.read', 'event_type.create', 'event_type.update', 'organization.read'])]
    private ?int $duration = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Assert\PositiveOrZero]
    #[Groups(['event_type.read', 'event_type.create', 'event_type.update', 'organization.read'])]
    private ?int $price = null;


    #[ORM\Column(enumType: EventPaymentConditionEnum::class)]
    #[Groups(['event_type.read', 'event_type.create', 'event_type.update', 'organization.read'])]
    private ?EventPaymentConditionEnum $reservation_payment_condition = null;

    #[ORM\Column(type: Types::INTEGER,nullable: true)]
    #[Assert\PositiveOrZero]
    #[Groups(['event_type.read', 'event_type.create', 'event_type.update', 'organization.read'])]
    private ?int $deposit_amount = null;

    #[ORM\Column]
    #[Groups(['event_type.read', 'event_type.create', 'event_type.update', 'organization.read'])]
    private ?bool $address_required = null;

    #[ORM\ManyToOne(inversedBy: 'event_types')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Organization $organization = null;

    /**
     * @var Collection<int, OrganizationUser>
     */
    #[ORM\ManyToMany(targetEntity: OrganizationUser::class, inversedBy: 'event_types')]
    private Collection $organization_users;

    /**
     * @var Collection<int, ScheduleEvent>
     */
    #[ORM\OneToMany(targetEntity: ScheduleEvent::class, mappedBy: 'event_type')]
    private Collection $schedule_events;

    public function __construct()
    {
        $this->address_required = false;
        $this->organization_users = new ArrayCollection();
        $this->schedule_events = new ArrayCollection();
        $this->reservation_payment_condition = EventPaymentConditionEnum::FREE;
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getReservationPaymentCondition(): ?EventPaymentConditionEnum
    {
        return $this->reservation_payment_condition;
    }

    public function setReservationPaymentCondition(EventPaymentConditionEnum $reservation_payment_condition): static
    {
        $this->reservation_payment_condition = $reservation_payment_condition;

        return $this;
    }

    public function getDepositAmount(): ?int
    {
        return $this->deposit_amount;
    }

    public function setDepositAmount(int $deposit_amount): static
    {
        $this->deposit_amount = $deposit_amount;

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

    public function addOrganizationUser(OrganizationUser $organization_user): static
    {
        if ($organization_user->getOrganization() !== $this->getOrganization()) {
            throw new \Exception('The user oganization must be the same as the event organization');
        }
        
        if (!$this->organization_users->contains($organization_user)) {
            $this->organization_users->add($organization_user);
        }

        return $this;
    }

    public function removeOrganizationUser(OrganizationUser $organization_user): static
    {
        $this->organization_users->removeElement($organization_user);

        return $this;
    }

    /**
     * @return Collection<int, ScheduleEvent>
     */
    public function getScheduleEvents(): Collection
    {
        return $this->schedule_events;
    }

    public function addScheduleEvent(ScheduleEvent $schedule_event): static
    {
        if (!$this->schedule_events->contains($schedule_event)) {
            $this->schedule_events->add($schedule_event);
            $schedule_event->setEventType($this);
        }

        return $this;
    }

    public function removeScheduleEvent(ScheduleEvent $schedule_event): static
    {
        if ($this->schedule_events->removeElement($schedule_event)) {
            // set the owning side to null (unless already changed)
            if ($schedule_event->getEventType() === $this) {
                $schedule_event->setEventType(null);
            }
        }

        return $this;
    }
}
