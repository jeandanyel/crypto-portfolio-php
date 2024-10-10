<?php

namespace App\Scheduler\Task;

use App\Importer\CryptocurrencyImporterInterface;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;

#[AsPeriodicTask(frequency: '1 day', from: '00:00:00')]
class ImportCryptocurrency
{
    public function __construct(private CryptocurrencyImporterInterface $cryptocurrencyImporter) {}

    public function __invoke()
    {
        $this->cryptocurrencyImporter->importFromCoinGecko();
    }
}
