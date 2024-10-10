<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\CryptocurrencyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CryptocurrencyRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Cryptocurrency
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['cryptocurrenct', 'asset', 'transaction'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['cryptocurrenct', 'asset', 'transaction'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['cryptocurrenct', 'asset', 'transaction'])]
    private ?string $symbol = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coinGeckoId = null;

    public function __toString()
    {
        return $this->name;
    }

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

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): static
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getCoinGeckoId(): ?string
    {
        return $this->coinGeckoId;
    }

    public function setCoinGeckoId(?string $coinGeckoId): static
    {
        $this->coinGeckoId = $coinGeckoId;

        return $this;
    }
}
