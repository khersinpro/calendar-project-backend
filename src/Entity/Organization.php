<?php

namespace App\Entity;

use App\Repository\OrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
class Organization
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, OrganizationUser>
     */
    #[ORM\OneToMany(targetEntity: OrganizationUser::class, mappedBy: 'organization', orphanRemoval: true)]
    private Collection $organization_users;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255, minMessage: 'The name must be at least 2 characters', maxMessage: 'The name cannot be longer than 255 characters')]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url]
    private ?string $website_url = null;

    /**
     * @var Collection<int, EventType>
     */
    #[ORM\OneToMany(targetEntity: EventType::class, mappedBy: 'organization', orphanRemoval: true)]
    private Collection $eventTypes;

    #[ORM\OneToOne(inversedBy: 'organization', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Address $address = null;

    public function __construct()
    {
        $this->organization_users = new ArrayCollection();
        $this->eventTypes = new ArrayCollection();
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
        return $this->eventTypes;
    }

    public function addEventType(EventType $eventType): static
    {
        if (!$this->eventTypes->contains($eventType)) {
            $this->eventTypes->add($eventType);
            $eventType->setOrganization($this);
        }

        return $this;
    }

    public function removeEventType(EventType $eventType): static
    {
        if ($this->eventTypes->removeElement($eventType)) {
            // set the owning side to null (unless already changed)
            if ($eventType->getOrganization() === $this) {
                $eventType->setOrganization(null);
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
}
