<?php

namespace App\Api;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CoinGeckoApi
{
    public function __construct(private HttpClientInterface $coingeckoClient) {}

    /**
     * Returns a list of cryptocurrencies with their symbols and names.
     */
    public function getCoinsList(): array
    {
        $response = $this->coingeckoClient->request('GET', 'coins/list');

        return json_decode($response->getContent());
    }
}
