<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\TransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Transaction
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    private ?float $fee = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(nullable: true)]
    private ?float $transactedQuantity = null;

    #[ORM\Column(nullable: true)]
    private ?float $receivedQuantity = null;

    #[ORM\ManyToOne(cascade: ['persist'])]
    private ?Asset $transactedAsset = null;

    #[ORM\ManyToOne(cascade: ['persist'])]
    private ?Asset $receivedAsset = null;

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
}
