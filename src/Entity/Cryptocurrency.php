<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Traits\TimestampableTrait;
use App\Filter\SearchFilter;
use App\Repository\CryptocurrencyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CryptocurrencyRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
    ],
    normalizationContext: ['groups' => ['cryptocurrency']],
    order: ['rank' => 'ASC'],
)]
#[ApiFilter(SearchFilter::class, properties: ['name', 'symbol'])]
class Cryptocurrency
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['cryptocurrency', 'asset', 'transaction'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['cryptocurrency', 'asset', 'transaction'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['cryptocurrency', 'asset', 'transaction'])]
    private ?string $symbol = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['cryptocurrency', 'asset', 'transaction'])]
    private ?string $coinGeckoId = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['cryptocurrency', 'asset', 'transaction'])]
    private ?int $coinMarketCapId = null;

    #[ORM\Column(nullable: true)]
    private ?int $rank = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['cryptocurrency', 'asset', 'transaction'])]
    private ?float $currentPrice = null;

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

    public function getCoinMarketCapId(): ?int
    {
        return $this->coinMarketCapId;
    }

    public function setCoinMarketCapId(?int $coinMarketCapId): static
    {
        $this->coinMarketCapId = $coinMarketCapId;

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(?int $rank): static
    {
        $this->rank = $rank;

        return $this;
    }

    public function getCurrentPrice(): ?float
    {
        return $this->currentPrice;
    }

    public function setCurrentPrice(?float $currentPrice): static
    {
        $this->currentPrice = $currentPrice;

        return $this;
    }
}
