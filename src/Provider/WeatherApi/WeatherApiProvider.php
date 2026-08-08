<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Provider\WeatherApi;

use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriFactoryInterface;
use Yaleksandr\Weather\Config\WeatherApiConfig;
use Yaleksandr\Weather\Contract\CurrentWeatherProvider;
use Yaleksandr\Weather\Exception\AuthenticationException;
use Yaleksandr\Weather\Exception\LocationNotFoundException;
use Yaleksandr\Weather\Exception\MalformedResponseException;
use Yaleksandr\Weather\Exception\ProviderUnavailableException;
use Yaleksandr\Weather\Exception\RateLimitException;
use Yaleksandr\Weather\Exception\TransportException;
use Yaleksandr\Weather\Exception\WeatherException;
use Yaleksandr\Weather\Model\CurrentWeather;
use Yaleksandr\Weather\Value\Coordinates;

final readonly class WeatherApiProvider implements CurrentWeatherProvider
{
    private const string ENDPOINT = 'https://api.weatherapi.com/v1/current.json';

    public function __construct(
        private WeatherApiConfig $config,
        private ClientInterface $client,
        private RequestFactoryInterface $requestFactory,
        private UriFactoryInterface $uriFactory,
    ) {}

    public function current(Coordinates $coordinates): CurrentWeather
    {
        $query = http_build_query([
            'key' => $this->config->apiKey(),
            'q' => $coordinates->latitude . ',' . $coordinates->longitude,
        ], '', '&', PHP_QUERY_RFC3986);
        $uri = $this->uriFactory->createUri(self::ENDPOINT)->withQuery($query);
        $request = $this->requestFactory->createRequest('GET', $uri);

        try {
            $response = $this->client->sendRequest($request);
        } catch (ClientExceptionInterface) {
            throw new TransportException('WeatherAPI request failed.');
        }

        $this->guardSuccessfulStatus($response);

        return WeatherApiCurrentWeatherMapper::map($this->decode($response), $coordinates);
    }

    private function guardSuccessfulStatus(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();

        if ($statusCode >= 200 && $statusCode <= 299) {
            return;
        }

        throw $this->exceptionFor($statusCode, $this->errorCode($response));
    }

    private function exceptionFor(int $statusCode, ?int $errorCode): WeatherException
    {
        return match ($errorCode) {
            1002, 2006, 2008, 2009 => new AuthenticationException('WeatherAPI authentication failed.'),
            2007 => new RateLimitException('WeatherAPI rate limit exceeded.'),
            1006 => new LocationNotFoundException('WeatherAPI location was not found.'),
            1003, 1005, 9999 => new ProviderUnavailableException('WeatherAPI is unavailable.'),
            default => match ($statusCode) {
                401, 403 => new AuthenticationException('WeatherAPI authentication failed.'),
                429 => new RateLimitException('WeatherAPI rate limit exceeded.'),
                default => new ProviderUnavailableException('WeatherAPI is unavailable.'),
            },
        };
    }

    private function errorCode(ResponseInterface $response): ?int
    {
        try {
            $payload = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return null;
        }

        if (!is_array($payload) || array_is_list($payload)) {
            return null;
        }

        $error = $payload['error'] ?? null;

        if (!is_array($error) || array_is_list($error)) {
            return null;
        }

        return is_int($error['code'] ?? null) ? $error['code'] : null;
    }

    /** @return array<string, mixed> */
    private function decode(ResponseInterface $response): array
    {
        try {
            $payload = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new MalformedResponseException('WeatherAPI returned invalid JSON.', 0, $exception);
        }

        if (!is_array($payload) || array_is_list($payload)) {
            throw new MalformedResponseException('WeatherAPI returned an invalid top-level JSON value.');
        }

        foreach (array_keys($payload) as $key) {
            if (!is_string($key)) {
                throw new MalformedResponseException('WeatherAPI returned an invalid top-level JSON object.');
            }
        }

        /** @var array<string, mixed> $payload */
        return $payload;
    }
}
