<?php

namespace App\Entity;

use App\Repository\EventTypeRepository;
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

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, options: ['default' => 0])]
    private ?string $price = null;

    #[ORM\Column]
    private ?bool $payment_required = null;

    #[ORM\Column]
    private ?bool $deposit_required = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, options: ['default' => 0])]
    private ?string $deposit_amout = null;

    #[ORM\Column]
    private ?bool $address_required = null;

    #[ORM\ManyToOne(inversedBy: 'eventTypes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Organization $organization = null;

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
}
