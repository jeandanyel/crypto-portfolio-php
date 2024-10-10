<?php

namespace App\Importer;

interface CryptocurrencyImporterInterface {
    public function importFromCoinGecko(): void;
}