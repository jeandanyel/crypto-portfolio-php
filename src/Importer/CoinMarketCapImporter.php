<?php

namespace App\Importer;

use App\Api\CoinMarketCapApi;
use App\Entity\Cryptocurrency;
use App\Message\CryptocurrencyLogoImport;
use App\Repository\CryptocurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CoinMarketCapImporter implements CryptocurrencyImporterInterface
{
    const PERSIST_COUNT_LIMIT = 50;
    const CRYPTOCURRENCY_LIMIT = 5000;

    public function __construct(
        private CryptocurrencyRepository $cryptocurrencyRepository,
        private EntityManagerInterface $entityManager,
        private CoinMarketCapApi $coinMarketCapApi,
        private MessageBusInterface $bus,
    ) {}
    
    public function import(): void
    {
        $this->entityManager->getConnection()->getConfiguration()->setMiddlewares([]);

        $page = 1;

        do {
            $start = ($page - 1) * self::CRYPTOCURRENCY_LIMIT + 1;
            $result = $this->coinMarketCapApi->getCryptocurrencyMap([
                'start' => $start, 
                'limit' => self::CRYPTOCURRENCY_LIMIT,
            ]);

            foreach (array_chunk($result, self::PERSIST_COUNT_LIMIT) as $coins) {
                $coinMarketCapIds = array_map(fn($coin) => $coin->id, $coins);
                $cryptocurrencies = $this->cryptocurrencyRepository->findByCoinMarketCapIds($coinMarketCapIds);

                foreach ($coins as $coin) {
                    $cryptocurrency = $cryptocurrencies[$coin->id] ?? new Cryptocurrency();

                    $cryptocurrency->setCoinMarketCapId($coin->id);
                    $cryptocurrency->setSymbol($coin->symbol);
                    $cryptocurrency->setName($coin->name);
                    $cryptocurrency->setRank($coin->rank);

                    $this->entityManager->persist($cryptocurrency);

                    $this->bus->dispatch(new CryptocurrencyLogoImport($coin->id));
                }

                $this->entityManager->flush();
                $this->entityManager->clear();
            }

            $page++;
        } while (count($result) === self::CRYPTOCURRENCY_LIMIT);
    }
}
