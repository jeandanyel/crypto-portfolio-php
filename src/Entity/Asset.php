<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\AssetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssetRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Asset
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cryptocurrency $cryptocurrency = null;

    #[ORM\Column]
    private ?float $quantity = 0;

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
}