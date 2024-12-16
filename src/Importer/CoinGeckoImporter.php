<?php

namespace App\Importer;

use App\Api\CoinGeckoApi;
use App\Entity\Cryptocurrency;
use App\Repository\CryptocurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;

class CoinGeckoImporter// implements CryptocurrencyImporterInterface
{
    const PERSIST_COUNT_LIMIT = 100;
    const MARKETS_DATA_FILE_PATH = __DIR__ . '/../../public/coins.json';

    public function __construct(
        private CryptocurrencyRepository $cryptocurrencyRepository,
        private EntityManagerInterface $entityManager,
        private CoinGeckoApi $coinGeckoApi,
    ) {}

    public function importMarkets(): void
    {
        set_time_limit(0);

        $page = 1;
        $requestCount = 0;
        $coins = [];

        do {
            $result = $this->coinGeckoApi->getCoinsMarkets(['vs_currency' => 'USD', 'per_page' => 250, 'page' => $page]);
            $coins = array_merge($coins, $result);

            $page++;
            $requestCount++;

            if ($requestCount % 30 === 0 || count($result) < 250) {
                file_put_contents(self::MARKETS_DATA_FILE_PATH, json_encode($coins, JSON_PRETTY_PRINT));
                sleep(60);
            }
        } while (count($result) === 250);


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
