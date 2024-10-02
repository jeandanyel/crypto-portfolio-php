<?php

namespace App\Handler;

use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;

class TransactionHandler implements TransactionHandlerInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}

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
        }

        $this->entityManager->flush();
    }
}
