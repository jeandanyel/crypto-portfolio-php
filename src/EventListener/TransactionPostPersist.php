<?php

namespace App\EventListener;

use App\Entity\Transaction;
use App\Handler\TransactionHandlerInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PostPersistEventArgs;

#[AsEntityListener(event: Events::postPersist, entity: Transaction::class)]
class TransactionPostPersist
{
    public function __construct(private TransactionHandlerInterface $transactionHandler) {}

    public function __invoke(Transaction $transaction, PostPersistEventArgs $event): void
    {
        $this->transactionHandler->process($transaction);
    }
}
