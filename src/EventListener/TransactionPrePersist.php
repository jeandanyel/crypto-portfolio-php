<?php

namespace App\EventListener;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Transaction::class)]
class TransactionPrePersist
{
    public function __construct(private Security $security)
    {
    }

    public function prePersist(Transaction $transaction, PrePersistEventArgs $event): void
    {
        $transaction->setUser($this->security->getUser());
    }
}
