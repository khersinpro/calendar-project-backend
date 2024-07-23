<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Service\EmailNormalizerService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user.read'])]
    private ?int $id = null;
    
    #[ORM\Column(length: 180)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(['user.read', 'user.create', 'user.update'])]
    private ?string $email = null;
    
    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Groups(['user.read'])]
    private array $roles = [];
    
    /**
     * @var string The hashed password
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 6, minMessage: 'The password must be at least 6 characters')]
    #[Groups(['user.create', 'user.update'])]
    private ?string $password = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2, 
        max: 255,
        minMessage: 'The firstname must be at least 2 characters',
        maxMessage: 'The firstname cannot be longer than 35 characters'
        )]
    #[Groups(['user.read', 'user.create', 'user.update'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2, 
        max: 255,
        minMessage: 'The firstname must be at least 2 characters',
        maxMessage: 'The firstname cannot be longer than 35 characters'
    )]

    #[Groups(['user.read', 'user.create', 'user.update'])]
    private ?string $lastname = null;

    /**
     * @var Collection<int, UserProvider>
     */
    #[Groups(['userProvider.read'])]
    #[ORM\OneToMany(targetEntity: UserProvider::class, mappedBy: 'user', orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $userProviders;

    /**
     * @var Collection<int, OrganizationUser>
     */
    #[ORM\OneToMany(targetEntity: OrganizationUser::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $organization_users;

    public function __construct()
    {
        $this->userProviders = new ArrayCollection();
        $this->organization_users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = EmailNormalizerService::normalizeEmail($email);
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }


    /**
     * @return Collection<int, UserProvider>
     */
    public function getUserProviders(): Collection
    {
        return $this->userProviders;
    }

    public function addUserProvider(UserProvider $userProvider): static
    {
        if (!$this->userProviders->contains($userProvider)) {
            $this->userProviders->add($userProvider);
            $userProvider->setUser($this);
        }

        return $this;
    }

    public function removeUserProvider(UserProvider $userProvider): static
    {
        if ($this->userProviders->removeElement($userProvider)) {
            // set the owning side to null (unless already changed)
            if ($userProvider->getUser() === $this) {
                $userProvider->setUser(null);
            }
        }

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
        if (!$this->organization_users->contains($organizationUser)) {
            $this->organization_users->add($organizationUser);
            $organizationUser->setUser($this);
        }

        return $this;
    }

    public function removeOrganizationUser(OrganizationUser $organizationUser): static
    {
        if ($this->organization_users->removeElement($organizationUser)) {
            // set the owning side to null (unless already changed)
            if ($organizationUser->getUser() === $this) {
                $organizationUser->setUser(null);
            }
        }

        return $this;
    }
}
