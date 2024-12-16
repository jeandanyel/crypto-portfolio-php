<?php

namespace App\Importer;

use App\Api\CoinGeckoApi;
use App\Api\CoinMarketCapApi;
use App\Entity\Cryptocurrency;
use App\Message\CryptocurrencyLogoImport;
use App\Repository\CryptocurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CryptocurrencyImporter implements CryptocurrencyImporterInterface
{
    const PERSIST_COUNT_LIMIT = 50;
    const CRYPTOCURRENCY_LIMIT = 5000;

    private array $mappedCoinGeckoCryptos = [];

    public function __construct(
        private CryptocurrencyRepository $cryptocurrencyRepository,
        private EntityManagerInterface $entityManager,
        private CoinMarketCapApi $coinMarketCapApi,
        private CoinGeckoApi $coinGeckoApi,
        private MessageBusInterface $bus,
    ) {}
    
    public function import(): void
    {
        $this->entityManager->getConnection()->getConfiguration()->setMiddlewares([]);

        $this->mapCoinGekcoCryptos();

        $page = 1;
    
        do {
            $start = ($page - 1) * self::CRYPTOCURRENCY_LIMIT + 1;
            $result = $this->coinMarketCapApi->getCryptocurrencyMap([
                'start' => $start, 
                'limit' => self::CRYPTOCURRENCY_LIMIT,
                'aux' => 'platform',
            ]);

            foreach (array_chunk($result, self::PERSIST_COUNT_LIMIT) as $coinMarketCapCryptos) {
                $coinMarketCapIds = array_map(fn($coinMarketCapCrypto) => $coinMarketCapCrypto->id, $coinMarketCapCryptos);
                $cryptocurrencies = $this->cryptocurrencyRepository->findByCoinMarketCapIds($coinMarketCapIds);

                foreach ($coinMarketCapCryptos as $coinMarketCapCrypto) {
                    $cryptocurrency = $cryptocurrencies[$coinMarketCapCrypto->id] ?? new Cryptocurrency();

                    $cryptocurrency->setCoinMarketCapId($coinMarketCapCrypto->id);
                    $cryptocurrency->setSymbol($coinMarketCapCrypto->symbol);
                    $cryptocurrency->setName($coinMarketCapCrypto->name);
                    $cryptocurrency->setRank($coinMarketCapCrypto->rank);

                    if (!$cryptocurrency->getCoinGeckoId()) {
                        $coinGeckoCrypto = $this->matchCryptocurrency($coinMarketCapCrypto);

                        if ($coinGeckoCrypto !== null) {
                            $cryptocurrency->setCoinGeckoId($coinGeckoCrypto->id);
                        }
                    }

                    $this->entityManager->persist($cryptocurrency);

                    if (!$cryptocurrency->getId()) {
                        $this->bus->dispatch(new CryptocurrencyLogoImport($coinMarketCapCrypto->id));
                    }
                }

                $this->entityManager->flush();
                $this->entityManager->clear();
            }

            $page++;
        } while (count($result) === self::CRYPTOCURRENCY_LIMIT);
    }

    private function mapCoinGekcoCryptos(): void
    {
        $coinGeckoCryptos = $this->coinGeckoApi->getCoinsList(['include_platform' => 'true']);

        foreach ($coinGeckoCryptos as $coinGeckoCrypto) {
            if (!$coinGeckoCrypto->symbol) {
                continue;
            }

            $symbol = strtolower($coinGeckoCrypto->symbol);

            $this->mappedCoinGeckoCryptos[$symbol][] = $coinGeckoCrypto;

            foreach ($coinGeckoCrypto->platforms ?? [] as $address) {
                if (!$address) {
                    continue;
                }

                $coinGekcoId = $coinGeckoCrypto->id;
                $address = strtolower($address);

                $this->mappedCoinGeckoCryptos[$address][$symbol][$coinGekcoId] = $coinGeckoCrypto;
            }
        }
    }

    private function matchCryptocurrency(object $coinMarketCapCrypto): ?object
    {
        $symbol = strtolower($coinMarketCapCrypto->symbol);
        $tokenAddress = $coinMarketCapCrypto->platform->token_address ?? null;

        if ($tokenAddress) {
            $tokenAddress = strtolower($tokenAddress);
            $coinGeckoCryptos = $this->mappedCoinGeckoCryptos[$tokenAddress][$symbol] ?? [];

            if ($coinGeckoCryptos) {
                if (count($coinGeckoCryptos) === 1) {
                    return reset($coinGeckoCryptos);
                } else {
                    $closestCrypto = $this->findClosestCoinGeckoCrypto($coinMarketCapCrypto, $coinGeckoCryptos, null);

                    return $closestCrypto;
                }
            }
        }
        
        $coinGeckoCryptos = $this->mappedCoinGeckoCryptos[$symbol] ?? [];

        return $this->findClosestCoinGeckoCrypto($coinMarketCapCrypto, $coinGeckoCryptos);
    }

    private function findClosestCoinGeckoCrypto(object $coinMarketCapCrypto, array $coinGeckoCryptos, ?int $distanceThreshold = 2): ?object
    {
        $closestDistance = null;
        $closestCrypto = null;

        foreach ($coinGeckoCryptos as $coinGeckoCrypto) {
            if (is_array($coinGeckoCrypto)) {
                continue;
            }

            $coinMarkCapName = strtolower($coinMarketCapCrypto->name);
            $coinGeckoName = strtolower($coinGeckoCrypto->name);

            $distance = levenshtein($coinMarkCapName, $coinGeckoName);

            if ($distance === 0) {
                return $coinGeckoCrypto;
            }

            if ($closestDistance === null || $distance < $closestDistance) {
                $closestDistance = $distance;
                $closestCrypto = $coinGeckoCrypto;
            }
        }

        if ($distanceThreshold && $closestDistance > $distanceThreshold) {
            return null;
        }

        return $closestCrypto;
    }
}
