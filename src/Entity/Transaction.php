<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\TimestampableTrait;
use App\Enum\TransactionType;
use App\Repository\TransactionRepository;
use App\Validator\Constraints as AppAssert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints\Choice;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['transaction']]
)]
#[AppAssert\TransactionAssetsValidation()]
class Transaction
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('transaction')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Choice(callback: [TransactionType::class, 'getValues'])]
    #[Groups('transaction')]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    #[Groups('transaction')]
    private ?float $fee = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups('transaction')]
    private ?string $notes = null;

    #[ORM\Column]
    #[Groups('transaction')]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(nullable: true)]
    #[Groups('transaction')]
    private ?float $transactedQuantity = null;

    #[ORM\Column(nullable: true)]
    #[Groups('transaction')]
    private ?float $receivedQuantity = null;

    #[ORM\ManyToOne(cascade: ['persist'])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'BTC'
        ],
    )]
    #[Groups('transaction')]
    private ?Asset $transactedAsset = null;

    #[ORM\ManyToOne(cascade: ['persist'])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'BTC'
        ]
    )]
    #[Groups('transaction')]
    private ?Asset $receivedAsset = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ApiProperty(readable: false, writable: false)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __clone()
    {
        $this->id = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getFee(): ?float
    {
        return $this->fee;
    }

    public function setFee(float $fee): static
    {
        $this->fee = $fee;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTransactedQuantity(): ?float
    {
        return $this->transactedQuantity;
    }

    public function setTransactedQuantity(float $transactedQuantity): static
    {
        $this->transactedQuantity = $transactedQuantity;

        return $this;
    }

    public function getReceivedQuantity(): ?float
    {
        return $this->receivedQuantity;
    }

    public function setReceivedQuantity(float $receivedQuantity): static
    {
        $this->receivedQuantity = $receivedQuantity;

        return $this;
    }

    public function getTransactedAsset(): ?Asset
    {
        return $this->transactedAsset;
    }

    public function setTransactedAsset(?Asset $transactedAsset): static
    {
        $this->transactedAsset = $transactedAsset;

        return $this;
    }

    public function getReceivedAsset(): ?Asset
    {
        return $this->receivedAsset;
    }

    public function setReceivedAsset(?Asset $receivedAsset): static
    {
        $this->receivedAsset = $receivedAsset;

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
