<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Tests\Provider\WeatherApi;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Yaleksandr\Weather\Exception\MalformedResponseException;
use Yaleksandr\Weather\Provider\WeatherApi\WeatherApiCurrentWeatherMapper;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Value\WeatherCondition;

final class WeatherApiCurrentWeatherMapperTest extends TestCase
{
    #[TestDox('Сопоставляет полный текущий ответ, сохраняя запрошенные координаты')]
    public function testItMapsCompleteCurrentWeatherResponse(): void
    {
        $requestedCoordinates = Coordinates::fromDegrees(55.7558, 37.6173);
        $weather = WeatherApiCurrentWeatherMapper::map(self::validPayload(), $requestedCoordinates);

        self::assertSame($requestedCoordinates, $weather->coordinates());
        self::assertSame('2026-08-08T13:25:00+00:00', $weather->observedAt()->format('c'));
        self::assertSame(21.4, $weather->temperature()->celsius());
        self::assertSame(21.0, $weather->feelsLike()?->celsius());
        self::assertSame(67.0, $weather->humidityPercent());
        self::assertSame(1014.2, $weather->pressureHectopascals());
        self::assertSame(WeatherCondition::Rain, $weather->condition());
        $wind = $weather->wind();
        self::assertNotNull($wind);
        self::assertEqualsWithDelta(3.4, $wind->speedMetersPerSecond(), 0.000001);
        self::assertSame(0.0, $wind->directionDegrees());
        self::assertEqualsWithDelta(6.1, $wind->gustMetersPerSecond(), 0.000001);
        self::assertSame(0.2, $weather->precipitationMillimeters());
    }

    /** @param array<string, mixed> $payload */
    #[DataProvider('malformedPayloads')]
    #[TestDox('Отклоняет malformed структуру и типы текущего ответа')]
    public function testItRejectsMalformedPayload(array $payload): void
    {
        $this->expectException(MalformedResponseException::class);

        WeatherApiCurrentWeatherMapper::map($payload, Coordinates::fromDegrees(55.7558, 37.6173));
    }

    #[TestDox('Преобразует некорректные canonical значения в ошибку provider response')]
    public function testItWrapsInvalidCanonicalValuesAsMalformedResponse(): void
    {
        $payload = self::validPayload();
        $payload['current']['humidity'] = 120;

        $this->expectException(MalformedResponseException::class);

        WeatherApiCurrentWeatherMapper::map($payload, Coordinates::fromDegrees(55.7558, 37.6173));
    }

    /** @return iterable<string, array{array<string, mixed>}> */
    public static function malformedPayloads(): iterable
    {
        yield 'missing current section' => [[]];

        $missingTemperature = self::validPayload();
        unset($missingTemperature['current']['temp_c']);
        yield 'missing required current field' => [$missingTemperature];

        $invalidCondition = self::validPayload();
        $invalidCondition['current']['condition'] = 'rain';
        yield 'condition is not an array' => [$invalidCondition];

        $missingConditionCode = self::validPayload();
        $missingConditionCode['current']['condition'] = [];
        yield 'condition code is missing' => [$missingConditionCode];

        $stringPrecipitation = self::validPayload();
        $stringPrecipitation['current']['precip_mm'] = '0.2';
        yield 'numeric field as string' => [$stringPrecipitation];

        $floatTimestamp = self::validPayload();
        $floatTimestamp['current']['last_updated_epoch'] = 1786195500.0;
        yield 'timestamp is not an integer' => [$floatTimestamp];
    }

    /**
     * @return array{
     *     location: array{lat: float, lon: float},
     *     current: array<string, mixed>
     * }
     */
    private static function validPayload(): array
    {
        return [
            'location' => [
                'lat' => 55.75,
                'lon' => 37.62,
            ],
            'current' => [
                'last_updated_epoch' => 1786195500,
                'temp_c' => 21.4,
                'feelslike_c' => 21.0,
                'humidity' => 67,
                'pressure_mb' => 1014.2,
                'precip_mm' => 0.2,
                'condition' => [
                    'code' => 1183,
                ],
                'wind_kph' => 12.24,
                'wind_degree' => 360,
                'gust_kph' => 21.96,
            ],
        ];
    }
}
