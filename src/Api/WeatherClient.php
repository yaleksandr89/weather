<?php

namespace Ya\Weather\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Stream;

class WeatherClient
{
    private const API_HOST = 'https://api.weatherapi.com/v1/current.json';

    private Client $httpClient;

    public function __construct(
        private readonly string $apiKey
    ) {
        $this->httpClient = new Client();
    }

    /**
     * @throws GuzzleException
     */
    public function request(string $query): array
    {
        $endpoint = self::API_HOST . '?' . http_build_query([
                'key' => $this->apiKey,
                'q' => $query,
            ]);

        $response = $this->httpClient
            ->request('GET', $endpoint)
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }
}
