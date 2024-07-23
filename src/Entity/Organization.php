<?php

namespace App\Entity;

use App\Repository\OrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
class Organization
{
    #[Groups(['organization.read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, OrganizationUser>
     */
    #[ORM\OneToMany(targetEntity: OrganizationUser::class, mappedBy: 'organization', orphanRemoval: true)]
    #[Groups(['organization.read'])]
    private Collection $organization_users;

    #[Groups(['organization.read', 'organization.create', 'organization.update'])]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255, minMessage: 'The name must be at least 2 characters', maxMessage: 'The name cannot be longer than 255 characters')]
    private ?string $name = null;

    #[Groups(['organization.read', 'organization.create', 'organization.update'])]
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Email]
    private ?string $email = null;

    #[Groups(['organization.read', 'organization.create', 'organization.update'])]
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url]
    private ?string $website_url = null;

    /**
     * @var Collection<int, EventType>
     */
    #[ORM\OneToMany(targetEntity: EventType::class, mappedBy: 'organization', orphanRemoval: true)]
    private Collection $event_types;

    #[ORM\OneToOne(inversedBy: 'organization', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Address $address = null;

    /**
     * @var Collection<int, Customer>
     */
    #[ORM\OneToMany(targetEntity: Customer::class, mappedBy: 'organization', orphanRemoval: true)]
    private Collection $customers;

    public function __construct()
    {
        $this->organization_users = new ArrayCollection();
        $this->event_types = new ArrayCollection();
        $this->customers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
        if (!$this->organization_users->contains($organizationUser)) {
            $this->organization_users->add($organizationUser);
            $organizationUser->setOrganization($this);
        }

        return $this;
    }

    public function removeOrganizationUser(OrganizationUser $organizationUser): static
    {
        if ($this->organization_users->removeElement($organizationUser)) {
            // set the owning side to null (unless already changed)
            if ($organizationUser->getOrganization() === $this) {
                $organizationUser->setOrganization(null);
            }
        }

        return $this;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getWebsiteUrl(): ?string
    {
        return $this->website_url;
    }

    public function setWebsiteUrl(?string $website_url): static
    {
        $this->website_url = $website_url;

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
        if (!$this->event_types->contains($event_type)) {
            $this->event_types->add($event_type);
            $event_type->setOrganization($this);
        }

        return $this;
    }

    public function removeEventType(EventType $event_type): static
    {
        if ($this->event_types->removeElement($event_type)) {
            // set the owning side to null (unless already changed)
            if ($event_type->getOrganization() === $this) {
                $event_type->setOrganization(null);
            }
        }

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Customer>
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): static
    {
        if (!$this->customers->contains($customer)) {
            $this->customers->add($customer);
            $customer->setOrganization($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): static
    {
        if ($this->customers->removeElement($customer)) {
            // set the owning side to null (unless already changed)
            if ($customer->getOrganization() === $this) {
                $customer->setOrganization(null);
            }
        }

        return $this;
    }
}
