<?php

namespace App\Entity;

use App\Enum\UserProviderEnum;
use App\Repository\UserProviderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserProviderRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_8D6F9AC48486F9AC', columns: ['unique_id', 'type'])]
class UserProvider
{
    #[Groups(['userProvider.read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[Groups(['userProvider.read'])]
    #[ORM\Column(enumType: UserProviderEnum::class)]
    private ?UserProviderEnum $type = null;
    
    #[Groups(['userProvider.read'])]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $access_token = null;
    
    #[Groups(['userProvider.read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $refresh_token = null;
    
    #[Groups(['userProvider.read'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $token_expire = null;
    
    #[ORM\ManyToOne(inversedBy: 'userProviders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;
    
    #[Groups(['userProvider.read'])]
    #[ORM\Column]
    private ?string $unique_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?UserProviderEnum
    {
        return $this->type;
    }

    public function setType(UserProviderEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->access_token;
    }

    public function setAccessToken(string $access_token): static
    {
        $this->access_token = $access_token;

        return $this;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refresh_token;
    }

    public function setRefreshToken(?string $refresh_token): static
    {
        $this->refresh_token = $refresh_token;

        return $this;
    }

    public function getTokenExpire(): ?\DateTimeInterface
    {
        return $this->token_expire;
    }

    public function setTokenExpire(?\DateTimeInterface $token_expire): static
    {
        $this->token_expire = $token_expire;

        return $this;
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

    public function getUniqueId(): ?string
    {
        return $this->unique_id;
    }

    public function setUniqueId(string $unique_id): static
    {
        $this->unique_id = $unique_id;

        return $this;
    }
}
