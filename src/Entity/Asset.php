<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\AssetRepository;
use App\State\AssetProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AssetRepository::class)]
#[ORM\UniqueConstraint(fields: ['cryptocurrency', 'user'])]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Get(
            provider: AssetProvider::class,
            uriTemplate: '/assets/{cryptocurrencyId}',
            uriVariables: ['cryptocurrencyId' => new Link(fromClass: self::class, identifiers: ['cryptocurrencyId'])],
        ),
        new GetCollection()
    ],
    normalizationContext: ['groups' => ['asset']]
)]
class Asset
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['asset', 'transaction'])]
    #[ApiProperty(identifier: false)]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['asset', 'transaction'])]
    private ?Cryptocurrency $cryptocurrency = null;

    #[ORM\Column]
    #[Groups(['asset', 'transaction'])]
    private ?float $quantity = 0;

    #[ORM\ManyToOne(inversedBy: 'assets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __toString()
    {
        return $this->cryptocurrency->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCryptocurrency(): ?Cryptocurrency
    {
        return $this->cryptocurrency;
    }

    public function setCryptocurrency(?Cryptocurrency $cryptocurrency): static
    {
        $this->cryptocurrency = $cryptocurrency;

        return $this;
    }
    
    #[ApiProperty(identifier: true)]
    public function getCryptocurrencyId(): ?string
    {
        return $this->cryptocurrency->getId();
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function addQuantity(float $quantity): static
    {
        $this->quantity += $quantity;

        return $this;
    }

    public function removeQuantity(float $quantity): static
    {
        $this->quantity -= $quantity;

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
}
