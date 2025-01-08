<?php

namespace App\Importer;

use App\Api\CoinGeckoApi;
use App\Repository\CryptocurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;

class CoinGeckoImporter
{
    const PERSIST_COUNT_LIMIT = 50;
    const COIN_GECKO_PAGE_LIMIT = 250;
    const MARKETS_DATA_FILE_PATH = __DIR__ . '/../../public/coins.json';

    public function __construct(
        private CryptocurrencyRepository $cryptocurrencyRepository,
        private EntityManagerInterface $entityManager,
        private CoinGeckoApi $coinGeckoApi,
    ) {}

    public function updatePrices(): void
    {
        set_time_limit(0);

        $page = 1;
        $requestCount = 0;

        do {
            $coinGeckoCryptos = $this->coinGeckoApi->getCoinsMarkets([
                'vs_currency' => 'USD', 
                'per_page' => self::COIN_GECKO_PAGE_LIMIT, 
                'page' => $page
            ]);

            $currentPrices = [];

            foreach ($coinGeckoCryptos as $coinGeckoCrypto) {
                $currentPrices[$coinGeckoCrypto->id] = $coinGeckoCrypto->current_price;
            }

            $cryptocurrencies = $this->cryptocurrencyRepository->findByCoinGeckoIds(array_keys($currentPrices));

            foreach ($cryptocurrencies as $cryptocurrency) {
                $coinGeckoId = $cryptocurrency->getCoinGeckoId();
                $currentPrice = $currentPrices[$coinGeckoId] ?? null;

                if ($currentPrice !== null) {
                    $cryptocurrency->setCurrentPrice($currentPrice);

                    $this->entityManager->persist($cryptocurrency);
                }
            }

            $this->entityManager->flush();

            $page++;
            $requestCount++;

            if ($requestCount % 30 === 0 || count($coinGeckoCryptos) < self::COIN_GECKO_PAGE_LIMIT) {
                sleep(60);
            }
        } while (count($coinGeckoCryptos) === self::COIN_GECKO_PAGE_LIMIT);
    }

    public function importMarkets(): void
    {
        set_time_limit(0);

        $page = 1;
        $requestCount = 0;
        $coins = [];

        do {
            $result = $this->coinGeckoApi->getCoinsMarkets([
                'vs_currency' => 'USD', 
                'per_page' => self::COIN_GECKO_PAGE_LIMIT, 
                'page' => $page
            ]);

            $coins = array_merge($coins, $result);

            $page++;
            $requestCount++;

            if ($requestCount % 30 === 0 || count($result) < self::COIN_GECKO_PAGE_LIMIT) {
                file_put_contents(self::MARKETS_DATA_FILE_PATH, json_encode($coins, JSON_PRETTY_PRINT));
                sleep(60);
            }
        } while (count($result) === self::COIN_GECKO_PAGE_LIMIT);


        return;
    }

    public function loadMarketsData(): array
    {
        $marketsData = [];

        if (file_exists(self::MARKETS_DATA_FILE_PATH)) {
            $marketsDataJson = file_get_contents(self::MARKETS_DATA_FILE_PATH);

            foreach (json_decode($marketsDataJson) as $data) {
                $marketsData[$data->id] = $data;
            }
        }

        return $marketsData;;
    }
}
