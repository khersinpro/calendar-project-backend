<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(nullable: true)]
    private ?int $postal_code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    /**
     * @var Collection<int, AddressComplement>
     */
    #[ORM\OneToMany(targetEntity: AddressComplement::class, mappedBy: 'address', orphanRemoval: true)]
    private Collection $address_complements;

    /**
     * @var Collection<int, PhoneNumber>
     */
    #[ORM\OneToMany(targetEntity: PhoneNumber::class, mappedBy: 'address', orphanRemoval: true)]
    private Collection $phone_numbers;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    private ?Country $country = null;

    #[ORM\OneToOne(mappedBy: 'address', cascade: ['persist', 'remove'])]
    private ?Organization $organization = null;

    public function __construct()
    {
        $this->address_complements = new ArrayCollection();
        $this->phone_numbers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postal_code;
    }

    public function setPostalCode(?int $postal_code): static
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, AddressComplement>
     */
    public function getAddressComplements(): Collection
    {
        return $this->address_complements;
    }

    public function addAddressComplement(AddressComplement $address_complement): static
    {
        if (!$this->address_complements->contains($address_complement)) {
            $this->address_complements->add($address_complement);
            $address_complement->setAddress($this);
        }

        return $this;
    }

    public function removeAddressComplement(AddressComplement $address_complement): static
    {
        if ($this->address_complements->removeElement($address_complement)) {
            // set the owning side to null (unless already changed)
            if ($address_complement->getAddress() === $this) {
                $address_complement->setAddress(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PhoneNumber>
     */
    public function getPhoneNumbers(): Collection
    {
        return $this->phone_numbers;
    }

    public function addPhoneNumber(PhoneNumber $phone_number): static
    {
        if (!$this->phone_numbers->contains($phone_number)) {
            $this->phone_numbers->add($phone_number);
            $phone_number->setAddress($this);
        }

        return $this;
    }

    public function removePhoneNumber(PhoneNumber $phone_number): static
    {
        if ($this->phone_numbers->removeElement($phone_number)) {
            // set the owning side to null (unless already changed)
            if ($phone_number->getAddress() === $this) {
                $phone_number->setAddress(null);
            }
        }

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): static
    {
        // unset the owning side of the relation if necessary
        if ($organization === null && $this->organization !== null) {
            $this->organization->setAddress(null);
        }

        // set the owning side of the relation if necessary
        if ($organization !== null && $organization->getAddress() !== $this) {
            $organization->setAddress($this);
        }

        $this->organization = $organization;

        return $this;
    }
}
