<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\AssetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AssetRepository::class)]
#[ORM\UniqueConstraint(fields: ['cryptocurrency', 'user'])]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Get(),
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
    #[ApiProperty(identifier: true)]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['asset', 'transaction'])]
    private ?Cryptocurrency $cryptocurrency = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero()]
    #[Groups(['asset', 'transaction',])]
    private ?float $quantity = 0;

    #[ORM\ManyToOne(inversedBy: 'assets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, SellStrategy>
     */
    #[ORM\OneToMany(targetEntity: SellStrategy::class, mappedBy: 'asset', orphanRemoval: true)]
    private Collection $sellStrategies;

    public function __construct()
    {
        $this->sellStrategies = new ArrayCollection();
    }

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
    
    // #[ApiProperty(identifier: true)]
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

    /**
     * @return Collection<int, SellStrategy>
     */
    public function getSellStrategies(): Collection
    {
        return $this->sellStrategies;
    }

    public function addSellStrategy(SellStrategy $sellStrategy): static
    {
        if (!$this->sellStrategies->contains($sellStrategy)) {
            $this->sellStrategies->add($sellStrategy);
            $sellStrategy->setAsset($this);
        }

        return $this;
    }

    public function removeSellStrategy(SellStrategy $sellStrategy): static
    {
        if ($this->sellStrategies->removeElement($sellStrategy)) {
            // set the owning side to null (unless already changed)
            if ($sellStrategy->getAsset() === $this) {
                $sellStrategy->setAsset(null);
            }
        }

        return $this;
    }
}
