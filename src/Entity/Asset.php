<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\AssetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AssetRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Asset
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['asset', 'transaction'])]
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
