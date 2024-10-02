<?php

namespace App\EventListener;

use App\Entity\Transaction;
use App\Handler\TransactionHandlerInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postRemove, entity: Transaction::class)]
class TransactionPostRemove
{
    public function __construct(private TransactionHandlerInterface $transactionHandler) {}

    public function __invoke(Transaction $transaction, PostRemoveEventArgs $event): void
    {
        $this->transactionHandler->revert($transaction);
    }
}
