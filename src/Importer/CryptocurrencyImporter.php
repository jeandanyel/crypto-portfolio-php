<?php

namespace App\Importer;

use App\Api\CoinGeckoApi;
use App\Entity\Cryptocurrency;
use App\Repository\CryptocurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;

class CryptocurrencyImporter implements CryptocurrencyImporterInterface
{
    const PERSIST_COUNT_LIMIT = 100;

    public function __construct(
        private CryptocurrencyRepository $cryptocurrencyRepository,
        private EntityManagerInterface $entityManager,
        private CoinGeckoApi $coinGeckoApi
    ) {}

    public function importFromCoinGecko(): void
    {
        $coinsList = $this->coinGeckoApi->getCoinsList();

        $this->entityManager->getConnection()->getConfiguration()->setMiddlewares([]);

        foreach (array_chunk($coinsList, self::PERSIST_COUNT_LIMIT) as $coins) {
            $coinGeckoIds = array_map(fn($coin) => $coin->id, $coins);
            $cryptocurrencies = $this->cryptocurrencyRepository->findByCoinGeckoIds($coinGeckoIds);

            foreach ($coins as $coin) {
                $cryptocurrency = $cryptocurrencies[$coin->id] ?? new Cryptocurrency();

                $cryptocurrency->setCoinGeckoId($coin->id);
                $cryptocurrency->setSymbol($coin->symbol);
                $cryptocurrency->setName($coin->name);

                $this->entityManager->persist($cryptocurrency);
            }

            $this->entityManager->flush();
            $this->entityManager->clear();
        }
    }
}
