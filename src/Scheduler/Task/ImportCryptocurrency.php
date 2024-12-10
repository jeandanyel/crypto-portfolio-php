<?php

namespace App\Scheduler\Task;

use App\Importer\CoinMarketCapImporter;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;

#[AsPeriodicTask(frequency: '1 day', from: '00:00:00')]
class ImportCryptocurrency
{
   public function __construct(private CoinMarketCapImporter $coinMarketCapImporter) {}

    public function __invoke()
    {
        $this->coinMarketCapImporter->import();
    }
}
