<?php

namespace App\EventListener;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, entity: Transaction::class)]
class TransactionPrePersist
{
    public function __construct(private Security $security) {}

    public function __invoke(Transaction $transaction, PrePersistEventArgs $event): void
    {
        $transaction->setUser($this->security->getUser());
    }
}
