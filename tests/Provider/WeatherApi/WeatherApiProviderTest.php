<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Tests\Provider\WeatherApi;

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
use Yaleksandr\Weather\Config\WeatherApiConfig;
use Yaleksandr\Weather\Exception\AuthenticationException;
use Yaleksandr\Weather\Exception\LocationNotFoundException;
use Yaleksandr\Weather\Exception\MalformedResponseException;
use Yaleksandr\Weather\Exception\ProviderUnavailableException;
use Yaleksandr\Weather\Exception\RateLimitException;
use Yaleksandr\Weather\Exception\TransportException;
use Yaleksandr\Weather\Exception\WeatherException;
use Yaleksandr\Weather\Provider\WeatherApi\WeatherApiProvider;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Value\WeatherCondition;

final class WeatherApiProviderTest extends TestCase
{
    /**
     * @throws JsonException
     */
    #[TestDox('Отправляет точный запрос WeatherAPI и сопоставляет текущую погоду')]
    public function testItRequestsAndMapsCurrentWeather(): void
    {
        $client = new WeatherApiRecordingClient(new Response(200, [], self::validPayloadJson()));
        $provider = self::provider($client);
        $coordinates = Coordinates::fromDegrees(55.7558, 37.6173);

        $weather = $provider->current($coordinates);

        $request = $client->request();
        $uri = $request->getUri();
        self::assertSame('GET', $request->getMethod());
        self::assertSame('https', $uri->getScheme());
        self::assertSame('api.weatherapi.com', $uri->getHost());
        self::assertSame('/v1/current.json', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        self::assertSame('test-api-key', $query['key'] ?? null);
        self::assertSame('55.7558,37.6173', $query['q'] ?? null);

        self::assertSame($coordinates, $weather->coordinates());
        self::assertSame(WeatherCondition::Rain, $weather->condition());
        self::assertSame(21.4, $weather->temperature()->celsius());
        self::assertSame('2026-08-08T13:25:00+00:00', $weather->observedAt()->format('c'));
    }

    #[TestDox('Не раскрывает ключ WeatherAPI при ошибке PSR-18 транспорта')]
    public function testItRedactsSecretFromTransportFailure(): void
    {
        $provider = self::provider(new class implements ClientInterface {
            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                throw new WeatherApiClientFailure(
                    'Request failed for ' . (string) $request->getUri(),
                );
            }
        });

        try {
            $provider->current(Coordinates::fromDegrees(55.7558, 37.6173));
            self::fail('Expected transport exception was not thrown.');
        } catch (TransportException $exception) {
            self::assertStringNotContainsString('test-api-key', (string) $exception);
            self::assertNull($exception->getPrevious());
        }
    }

    /** @param class-string<WeatherException> $expectedException
     * @throws JsonException
     */
    #[DataProvider('documentedErrorCodes')]
    #[TestDox('Классифицирует документированные коды ошибок WeatherAPI')]
    public function testItClassifiesDocumentedErrorCode(int $statusCode, int $errorCode, string $expectedException): void
    {
        $provider = self::provider(new WeatherApiRecordingClient(new Response(
            $statusCode,
            [],
            json_encode(['error' => ['code' => $errorCode, 'message' => 'provider text']], JSON_THROW_ON_ERROR),
        )));

        $this->expectException($expectedException);

        $provider->current(Coordinates::fromDegrees(55.7558, 37.6173));
    }

    /** @param class-string<WeatherException> $expectedException */
    #[DataProvider('httpFallbacks')]
    #[TestDox('Использует HTTP fallback для непригодного error body')]
    public function testItClassifiesHttpFallback(int $statusCode, string $body, string $expectedException): void
    {
        $provider = self::provider(new WeatherApiRecordingClient(new Response($statusCode, [], $body)));

        $this->expectException($expectedException);

        $provider->current(Coordinates::fromDegrees(55.7558, 37.6173));
    }

    #[TestDox('Отклоняет синтаксически некорректный успешный JSON')]
    public function testItRejectsInvalidSuccessfulJson(): void
    {
        $provider = self::provider(new WeatherApiRecordingClient(new Response(200, [], '{invalid')));

        $this->expectException(MalformedResponseException::class);

        $provider->current(Coordinates::fromDegrees(55.7558, 37.6173));
    }

    #[TestDox('Отклоняет успешный JSON list вместо объекта')]
    public function testItRejectsSuccessfulListTopLevelJson(): void
    {
        $provider = self::provider(new WeatherApiRecordingClient(new Response(200, [], '[]')));

        $this->expectException(MalformedResponseException::class);

        $provider->current(Coordinates::fromDegrees(55.7558, 37.6173));
    }

    /** @return iterable<string, array{int, int, class-string<WeatherException>}> */
    public static function documentedErrorCodes(): iterable
    {
        yield 'key is missing' => [401, 1002, AuthenticationException::class];
        yield 'key is invalid' => [401, 2006, AuthenticationException::class];
        yield 'monthly quota exceeded' => [403, 2007, RateLimitException::class];
        yield 'key is disabled' => [403, 2008, AuthenticationException::class];
        yield 'key lacks resource access' => [403, 2009, AuthenticationException::class];
        yield 'location is not found' => [400, 1006, LocationNotFoundException::class];
        yield 'query is missing' => [400, 1003, ProviderUnavailableException::class];
        yield 'request URL is invalid' => [400, 1005, ProviderUnavailableException::class];
        yield 'internal application error' => [400, 9999, ProviderUnavailableException::class];
    }

    /** @return iterable<string, array{int, string, class-string<WeatherException>}> */
    public static function httpFallbacks(): iterable
    {
        yield 'rate limited with malformed body' => [429, '{invalid', RateLimitException::class];
        yield 'service unavailable with missing body' => [503, '', ProviderUnavailableException::class];
    }

    private static function provider(ClientInterface $client): WeatherApiProvider
    {
        $httpFactory = new HttpFactory();

        return new WeatherApiProvider(
            new WeatherApiConfig('test-api-key'),
            $client,
            $httpFactory,
            $httpFactory,
        );
    }

    private static function validPayloadJson(): string
    {
        return json_encode([
            'current' => [
                'last_updated_epoch' => 1786195500,
                'temp_c' => 21.4,
                'feelslike_c' => 21.0,
                'humidity' => 67,
                'pressure_mb' => 1014.2,
                'precip_mm' => 0.2,
                'condition' => ['code' => 1183],
                'wind_kph' => 12.24,
                'wind_degree' => 360,
                'gust_kph' => 21.96,
            ],
        ], JSON_THROW_ON_ERROR);
    }
}

final class WeatherApiRecordingClient implements ClientInterface
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

final class WeatherApiClientFailure extends RuntimeException implements ClientExceptionInterface {}
