<?php

namespace App\Api;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CoinGeckoApi
{
    private HttpClientInterface $client;

    public function __construct(private HttpClientInterface $coinGeckoClient) {
        $this->client = $coinGeckoClient;
    }

    /**
     * Returns a list of cryptocurrencies with their symbols and names.
     */
    public function getCoinsList(array $parameters = []): array
    {
        $response = $this->client->request('GET', 'coins/list', [
            'query' => $parameters,
        ]);

        return json_decode($response->getContent());
    }

    public function getCoinsMarkets(array $parameters = []): array
    {
        $response = $this->client->request('GET', 'coins/markets', [
            'query' => $parameters,
        ]);

        return json_decode($response->getContent());
    }
}
