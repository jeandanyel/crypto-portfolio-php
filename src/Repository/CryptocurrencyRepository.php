<?php

namespace App\Repository;

use App\Entity\Cryptocurrency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cryptocurrency>
 *
 * @method Cryptocurrency|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cryptocurrency|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cryptocurrency[]    findAll()
 * @method Cryptocurrency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CryptocurrencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cryptocurrency::class);
    }

    /**
     * @param array<string> $ids
     * @return array<string, Cryptocurrency> Returns an array of Cryptocurrency objects
     */
    public function findByCoinGeckoIds(array $ids): array
    {
        $cryptocurrencies = [];

        foreach ($this->findBy(['coinGeckoId' => $ids]) as $cryptocurrency) {
            $coinGeckoId = $cryptocurrency->getCoinGeckoId();

            $cryptocurrencies[$coinGeckoId] = $cryptocurrency;
        }

        return $cryptocurrencies;
    }

    /**
     * @param array<int> $ids
     * @return array<int, Cryptocurrency> Returns an array of Cryptocurrency objects
     */
    public function findByCoinMarketCapIds(array $ids): array
    {
        $cryptocurrencies = [];

        foreach ($this->findBy(['coinMarketCapId' => $ids]) as $cryptocurrency) {
            $coinMarketCapId = $cryptocurrency->getCoinMarketCapId();

            $cryptocurrencies[$coinMarketCapId] = $cryptocurrency;
        }

        return $cryptocurrencies;
    }

    /**
     * @param array<int> $ids
     * @return array<int, Cryptocurrency> Returns an array of Cryptocurrency objects
     */
    public function findAllOfCoinMarketCap(): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.coinMarketCapId IS NOT NULL')
            ->orderBy('c.rank', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array<int> $ids
     * @return array<int, Cryptocurrency> Returns an array of Cryptocurrency objects
     */
    public function findAllOfCoinGecko(): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.coinGeckoId IS NOT NULL')
            ->getQuery()
            ->getResult();
    }
}
