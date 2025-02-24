<?php

namespace App\Handler;

use App\Entity\Transaction;
use App\Enum\TransactionType;
use App\Manager\AssetManagerInterface;
use Doctrine\ORM\EntityManagerInterface;

class TransactionHandler implements TransactionHandlerInterface
{
    private const TYPES_TO_RECALCULATE = [
        TransactionType::BUY->value,
        TransactionType::TRADE->value,
        TransactionType::SWAP->value
    ];

    public function __construct(
        private AssetManagerInterface $assetManager,
        private EntityManagerInterface $entityManager
    ) {}

    public function process(Transaction $transaction): void
    {
        $transactedAsset = $transaction->getTransactedAsset();
        $receivedAsset = $transaction->getReceivedAsset();

        if ($transactedAsset) {
            $transactedAsset->removeQuantity($transaction->getTransactedQuantity());

            $this->entityManager->persist($transactedAsset);
        }

        if ($receivedAsset) {
            $receivedAsset->addQuantity($transaction->getReceivedQuantity());

            $this->entityManager->persist($receivedAsset);

            if (in_array($transaction->getType(), self::TYPES_TO_RECALCULATE)) {
                $this->assetManager->calculateInvestment($receivedAsset);
            }
        }

        $this->entityManager->flush();        
    }

    public function revert(Transaction $transaction): void
    {
        $transactedAsset = $transaction->getTransactedAsset();
        $receivedAsset = $transaction->getReceivedAsset();

        if ($transactedAsset) {
            $transactedAsset->addQuantity($transaction->getTransactedQuantity());

            $this->entityManager->persist($transactedAsset);
        }

        if ($receivedAsset) {
            $receivedAsset->removeQuantity($transaction->getReceivedQuantity());

            $this->entityManager->persist($receivedAsset);

            if (in_array($transaction->getType(), self::TYPES_TO_RECALCULATE)) {
                $this->assetManager->calculateInvestment($receivedAsset);
            }
        }

        $this->entityManager->flush();
    }
}
