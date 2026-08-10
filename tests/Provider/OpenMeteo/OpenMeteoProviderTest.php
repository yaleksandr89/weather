<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Tests\Provider\OpenMeteo;

use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use JsonException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Yaleksandr\Weather\Exception\AuthenticationException;
use Yaleksandr\Weather\Exception\MalformedResponseException;
use Yaleksandr\Weather\Exception\ProviderUnavailableException;
use Yaleksandr\Weather\Exception\RateLimitException;
use Yaleksandr\Weather\Exception\TransportException;
use Yaleksandr\Weather\Exception\WeatherException;
use Yaleksandr\Weather\Provider\OpenMeteo\OpenMeteoProvider;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Value\WeatherCondition;

final class OpenMeteoProviderTest extends TestCase
{
    private const string CURRENT_VARIABLES = 'temperature_2m,relative_humidity_2m,apparent_temperature,precipitation,weather_code,pressure_msl,wind_speed_10m,wind_direction_10m,wind_gusts_10m';

    /**
     * @throws JsonException
     */
    #[TestDox('Отправляет точный запрос Open-Meteo и сопоставляет текущую погоду')]
    public function testItRequestsAndMapsCurrentWeather(): void
    {
        $client = new OpenMeteoRecordingClient(new Response(200, [], self::validPayloadJson()));
        $provider = self::provider($client);
        $coordinates = Coordinates::fromDegrees(55.7558, 37.6173);

        $weather = $provider->current($coordinates);

        $request = $client->request();
        $uri = $request->getUri();
        self::assertSame('GET', $request->getMethod());
        self::assertSame('https', $uri->getScheme());
        self::assertSame('api.open-meteo.com', $uri->getHost());
        self::assertSame('/v1/forecast', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        self::assertSame('55.7558', $query['latitude'] ?? null);
        self::assertSame('37.6173', $query['longitude'] ?? null);
        self::assertSame(self::CURRENT_VARIABLES, $query['current'] ?? null);
        self::assertSame('celsius', $query['temperature_unit'] ?? null);
        self::assertSame('ms', $query['wind_speed_unit'] ?? null);
        self::assertSame('mm', $query['precipitation_unit'] ?? null);
        self::assertSame('unixtime', $query['timeformat'] ?? null);

        self::assertSame($coordinates, $weather->coordinates());
        self::assertSame(WeatherCondition::Rain, $weather->condition());
        self::assertSame(21.4, $weather->temperature()->celsius());
        self::assertSame('2026-08-08T11:10:00+00:00', $weather->observedAt()->format('c'));
    }

    #[TestDox('Преобразует ошибку PSR-18 транспорта в ошибку пакета')]
    public function testItMapsTransportFailure(): void
    {
        $provider = self::provider(new OpenMeteoRecordingClient(new OpenMeteoClientFailure('Network failure.')));

        $this->expectException(TransportException::class);

        $provider->current(Coordinates::fromDegrees(55.7558, 37.6173));
    }

    /** @param class-string<WeatherException> $expectedException */
    #[DataProvider('httpErrorStatuses')]
    #[TestDox('Классифицирует ошибочный HTTP-статус в taxonomy пакета')]
    public function testItClassifiesHttpErrorStatus(int $statusCode, string $expectedException): void
    {
        $provider = self::provider(new OpenMeteoRecordingClient(new Response($statusCode)));

        $this->expectException($expectedException);

        $provider->current(Coordinates::fromDegrees(55.7558, 37.6173));
    }

    #[TestDox('Отклоняет синтаксически некорректный JSON')]
    public function testItRejectsInvalidJson(): void
    {
        $provider = self::provider(new OpenMeteoRecordingClient(new Response(200, [], '{invalid')));

        $this->expectException(MalformedResponseException::class);

        $provider->current(Coordinates::fromDegrees(55.7558, 37.6173));
    }

    #[TestDox('Отклоняет JSON list вместо объекта одной локации')]
    public function testItRejectsListTopLevelJson(): void
    {
        $provider = self::provider(new OpenMeteoRecordingClient(new Response(200, [], '[]')));

        $this->expectException(MalformedResponseException::class);

        $provider->current(Coordinates::fromDegrees(55.7558, 37.6173));
    }

    #[TestDox('Отклоняет успешный JSON object с числовым ключом верхнего уровня')]
    public function testItRejectsSuccessfulJsonObjectWithNumericTopLevelKey(): void
    {
        $provider = self::provider(new OpenMeteoRecordingClient(new Response(200, [], '{"1":"invalid"}')));

        $this->expectException(MalformedResponseException::class);

        $provider->current(Coordinates::fromDegrees(55.7558, 37.6173));
    }

    /** @return iterable<string, array{int, class-string<WeatherException>}> */
    public static function httpErrorStatuses(): iterable
    {
        yield 'unauthorized' => [401, AuthenticationException::class];
        yield 'forbidden' => [403, AuthenticationException::class];
        yield 'rate limited' => [429, RateLimitException::class];
        yield 'internal server error' => [500, ProviderUnavailableException::class];
        yield 'service unavailable' => [503, ProviderUnavailableException::class];
        yield 'bad request' => [400, ProviderUnavailableException::class];
    }

    private static function provider(OpenMeteoRecordingClient $client): OpenMeteoProvider
    {
        $httpFactory = new HttpFactory();

        return new OpenMeteoProvider($client, $httpFactory, $httpFactory);
    }

    private static function validPayloadJson(): string
    {
        return json_encode([
            'current' => [
                'time' => 1786187400,
                'temperature_2m' => 21.4,
                'relative_humidity_2m' => 67,
                'apparent_temperature' => 21.0,
                'precipitation' => 0.2,
                'weather_code' => 61,
                'pressure_msl' => 1014.2,
                'wind_speed_10m' => 3.4,
                'wind_direction_10m' => 240,
                'wind_gusts_10m' => 6.1,
            ],
        ], JSON_THROW_ON_ERROR);
    }
}

final class OpenMeteoRecordingClient implements ClientInterface
{
    private ?RequestInterface $request = null;

    public function __construct(
        private readonly ResponseInterface|ClientExceptionInterface $result,
    ) {}

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->request = $request;

        if ($this->result instanceof ClientExceptionInterface) {
            throw $this->result;
        }

        return $this->result;
    }

    public function request(): RequestInterface
    {
        return $this->request ?? throw new RuntimeException('No request was sent.');
    }
}

final class OpenMeteoClientFailure extends RuntimeException implements ClientExceptionInterface {}
