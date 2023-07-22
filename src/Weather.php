<?php

namespace Ya\Weather;

use GuzzleHttp\Exception\GuzzleException;
use IlluminateAgnostic\Arr\Support\Arr;
use Ya\Weather\Api\WeatherClient;
use Ya\Weather\Models\Result;

class Weather
{
    private string $location;

    private WeatherClient $client;

    public function __construct(
        private readonly string $apiKey
    ) {
        $this->client = new WeatherClient($apiKey);
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @throws GuzzleException
     */
    public function get(): Result
    {
        $data = $this->client
            ->request($this->location);

        return (new Result())
            ->setCelsius(Arr::get($data, 'current.temp_c'))
            ->setFahrenheit(Arr::get($data, 'current.temp_f'));
    }
}
