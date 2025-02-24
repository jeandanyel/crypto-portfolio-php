<?php

namespace App\Repository;

use App\Entity\Asset;
use App\Entity\Transaction;
use App\Enum\TransactionType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 *
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    /**
     * Calculates the total invested for BUY transactions.
     */
    public function getTotalInvestedForAsset(Asset $asset): float
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->select('SUM(t.receivedQuantity * t.receivedAssetPrice) AS totalInvested')
            ->where('t.receivedAsset = :asset')
            ->andWhere('t.type = :buy')
            ->setParameter('asset', $asset)
            ->setParameter('buy', TransactionType::BUY->value);

        $result = $queryBuilder->getQuery()->getSingleResult();

        return (float) ($result['totalInvested'] ?? 0);
    }

    /**
     * Calculates the average buy price for BUY, TRADE, and SWAP transactions.
     */
    public function getAverageBuyPriceForAsset($asset): float
    {
        $types = [
            TransactionType::BUY->value, 
            TransactionType::TRADE->value, 
            TransactionType::SWAP->value
        ];

        $queryBuilder = $this->createQueryBuilder('t')
            ->select(
                'SUM(t.receivedQuantity * t.receivedAssetPrice) AS totalInvested',
                'SUM(t.receivedQuantity) AS totalQuantity'
            )
            ->where('t.receivedAsset = :asset')
            ->andWhere('t.type IN (:types)')
            ->setParameter('asset', $asset)
            ->setParameter('types', $types, ArrayParameterType::STRING);

        $result = $queryBuilder->getQuery()->getSingleResult();

        $totalInvested = (float) ($result['totalInvested'] ?? 0);
        $totalQuantity = (float) ($result['totalQuantity'] ?? 0);

        return $totalQuantity === 0 ? 0 : $totalInvested / $totalQuantity;
    }
}
