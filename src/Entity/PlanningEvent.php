<?php

namespace App\Entity;

use App\Enum\PlanningEventStatusEnum;
use App\Repository\PlanningEventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanningEventRepository::class)]
class PlanningEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column(enumType: PlanningEventStatusEnum::class)]
    private ?PlanningEventStatusEnum $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $guest_first_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $guest_last_name = null;

    #[ORM\Column(nullable: true)]
    private ?int $guest_phone = null;

    #[ORM\ManyToOne(inversedBy: 'planningEvents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Planning $planning = null;

    #[ORM\ManyToOne(inversedBy: 'planningEvents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EventType $event_type = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Address $adress = null;

    #[ORM\ManyToOne(inversedBy: 'planing_events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getStatus(): ?PlanningEventStatusEnum
    {
        return $this->status;
    }

    public function setStatus(PlanningEventStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getGuestFirstName(): ?string
    {
        return $this->guest_first_name;
    }

    public function setGuestFirstName(?string $guest_first_name): static
    {
        $this->guest_first_name = $guest_first_name;

        return $this;
    }

    public function getGuestLastName(): ?string
    {
        return $this->guest_last_name;
    }

    public function setGuestLastName(?string $guest_last_name): static
    {
        $this->guest_last_name = $guest_last_name;

        return $this;
    }

    public function getGuestPhone(): ?int
    {
        return $this->guest_phone;
    }

    public function setGuestPhone(?int $guest_phone): static
    {
        $this->guest_phone = $guest_phone;

        return $this;
    }

    public function getPlanning(): ?Planning
    {
        return $this->planning;
    }

    public function setPlanning(?Planning $planning): static
    {
        $this->planning = $planning;

        return $this;
    }

    public function getEventType(): ?EventType
    {
        return $this->event_type;
    }

    public function setEventType(?EventType $event_type): static
    {
        $this->event_type = $event_type;

        return $this;
    }

    public function getAdress(): ?Address
    {
        return $this->adress;
    }

    public function setAdress(Address $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }
}
