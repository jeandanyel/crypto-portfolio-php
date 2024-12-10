<?php

namespace App\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
class CryptocurrencyLogoImport
{
    public function __construct(private int $coinMarketCapId) {}

    public function getCoinMarketCapId(): string
    {
        return $this->coinMarketCapId;
    }
}
