<?php

namespace App\Importer;

interface CryptocurrencyImporterInterface {
    public function import(): void;
}
