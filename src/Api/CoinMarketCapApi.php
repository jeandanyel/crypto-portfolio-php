<?php

namespace App\Api;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CoinMarketCapApi
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $coinMarketCapClient) {
        $this->client = $coinMarketCapClient;
    }

    /**
     * Returns a mapping of all cryptocurrencies to unique CoinMarketCap ids.
     */
    public function getCryptocurrencyMap(array $parameters = []): array
    {
        $response = $this->client->request('GET', 'cryptocurrency/map', [
            'query' => $parameters
        ]);

        $content = json_decode($response->getContent());
        
        return $content->data;
    }
}
