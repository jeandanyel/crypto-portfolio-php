<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SellStrategyRepository;
use App\Validator\Constraints as AppAssert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SellStrategyRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['sell_strategy']],
    denormalizationContext: ['groups' => ['sell_strategy']],
)]
#[AppAssert\SellStrategyPercentageValidation()]
class SellStrategy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('sell_strategy')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'sellStrategies')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('sell_strategy')]
    private ?Asset $asset = null;

    #[ORM\Column]
    #[Assert\Positive()]
    #[Groups('sell_strategy')]
    private ?float $percentage = null;

    #[ORM\Column]
    #[Assert\Positive()]
    #[Groups('sell_strategy')]
    private ?float $price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAsset(): ?Asset
    {
        return $this->asset;
    }

    public function setAsset(?Asset $asset): static
    {
        $this->asset = $asset;

        return $this;
    }

    public function getPercentage(): ?float
    {
        return $this->percentage;
    }

    public function setPercentage(float $percentage): static
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }
}
