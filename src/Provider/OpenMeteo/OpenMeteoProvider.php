<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Provider\OpenMeteo;

use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriFactoryInterface;
use Yaleksandr\Weather\Contract\CurrentWeatherProvider;
use Yaleksandr\Weather\Exception\AuthenticationException;
use Yaleksandr\Weather\Exception\MalformedResponseException;
use Yaleksandr\Weather\Exception\ProviderUnavailableException;
use Yaleksandr\Weather\Exception\RateLimitException;
use Yaleksandr\Weather\Exception\TransportException;
use Yaleksandr\Weather\Model\CurrentWeather;
use Yaleksandr\Weather\Value\Coordinates;

final readonly class OpenMeteoProvider implements CurrentWeatherProvider
{
    private const string ENDPOINT = 'https://api.open-meteo.com/v1/forecast';
    private const string CURRENT_VARIABLES = 'temperature_2m,relative_humidity_2m,apparent_temperature,precipitation,weather_code,pressure_msl,wind_speed_10m,wind_direction_10m,wind_gusts_10m';

    public function __construct(
        private ClientInterface $client,
        private RequestFactoryInterface $requestFactory,
        private UriFactoryInterface $uriFactory,
    ) {}

    public function current(Coordinates $coordinates): CurrentWeather
    {
        $query = http_build_query([
            'latitude' => $coordinates->latitude,
            'longitude' => $coordinates->longitude,
            'current' => self::CURRENT_VARIABLES,
            'temperature_unit' => 'celsius',
            'wind_speed_unit' => 'ms',
            'precipitation_unit' => 'mm',
            'timeformat' => 'unixtime',
        ], '', '&', PHP_QUERY_RFC3986);
        $uri = $this->uriFactory->createUri(self::ENDPOINT)->withQuery($query);
        $request = $this->requestFactory->createRequest('GET', $uri);

        try {
            $response = $this->client->sendRequest($request);
        } catch (ClientExceptionInterface $exception) {
            throw new TransportException('Open-Meteo request failed.', 0, $exception);
        }

        $this->guardSuccessfulStatus($response->getStatusCode());

        return OpenMeteoCurrentWeatherMapper::map($this->decode($response), $coordinates);
    }

    private function guardSuccessfulStatus(int $statusCode): void
    {
        if ($statusCode >= 200 && $statusCode <= 299) {
            return;
        }

        throw match ($statusCode) {
            401, 403 => new AuthenticationException('Open-Meteo authentication failed.'),
            429 => new RateLimitException('Open-Meteo rate limit exceeded.'),
            default => new ProviderUnavailableException('Open-Meteo is unavailable.'),
        };
    }

    /** @return array<string, mixed> */
    private function decode(ResponseInterface $response): array
    {
        try {
            $payload = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new MalformedResponseException('Open-Meteo returned invalid JSON.', 0, $exception);
        }

        if (!is_array($payload) || array_is_list($payload)) {
            throw new MalformedResponseException('Open-Meteo returned an invalid top-level JSON value.');
        }

        foreach (array_keys($payload) as $key) {
            if (!is_string($key)) {
                throw new MalformedResponseException('Open-Meteo returned an invalid top-level JSON object.');
            }
        }

        /** @var array<string, mixed> $payload */
        return $payload;
    }
}
