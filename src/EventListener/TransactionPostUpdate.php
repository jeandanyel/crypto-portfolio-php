<?php

namespace App\EventListener;

use App\Entity\Transaction;
use App\Handler\TransactionHandlerInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Symfony\Component\PropertyAccess\PropertyAccess;

#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Transaction::class)]
class TransactionPostUpdate
{
    public function __construct(
        private TransactionHandlerInterface $transactionHandler,
        private EntityManagerInterface $entityManager,
    ) {}

    public function postUpdate(Transaction $transaction, PostUpdateEventArgs $event): void
    {
        $originalTransaction = $this->getOriginalTransaction($transaction);

        $this->transactionHandler->revert($originalTransaction);
        $this->transactionHandler->process($transaction);
    }

    private function getOriginalTransaction(Transaction $transaction): Transaction
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $unitOfWork = $this->entityManager->getUnitOfWork();
        $changeSet = $unitOfWork->getEntityChangeSet($transaction);
        $originalTransaction = clone $transaction;

        foreach ($changeSet as $field => [$oldValue, $newValue]) {
            $propertyAccessor->setValue($originalTransaction, $field, $oldValue);
        }

        return $originalTransaction;
    }
}
