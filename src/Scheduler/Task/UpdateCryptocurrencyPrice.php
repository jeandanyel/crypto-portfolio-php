<?php

namespace App\Scheduler\Task;

use App\Importer\CoinGeckoImporter;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;

#[AsPeriodicTask(frequency: '15 minutes')]
class UpdateCryptocurrencyPrice
{
   public function __construct(private CoinGeckoImporter $coinGeckoImporter) {}

    public function __invoke()
    {
        $this->coinGeckoImporter->updatePrices();
    }
}
