<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Tests\Provider\OpenMeteo;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Yaleksandr\Weather\Exception\MalformedResponseException;
use Yaleksandr\Weather\Provider\OpenMeteo\OpenMeteoCurrentWeatherMapper;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Value\WeatherCondition;

final class OpenMeteoCurrentWeatherMapperTest extends TestCase
{
    #[TestDox('Сопоставляет полный текущий ответ, сохраняя запрошенные координаты')]
    public function testItMapsCompleteCurrentWeatherResponse(): void
    {
        $requestedCoordinates = Coordinates::fromDegrees(55.7558, 37.6173);
        $weather = OpenMeteoCurrentWeatherMapper::map([
            'latitude' => 55.75,
            'longitude' => 37.625,
            'current' => [
                'time' => 1786187400,
                'temperature_2m' => 21.4,
                'relative_humidity_2m' => 67,
                'apparent_temperature' => 21.0,
                'precipitation' => 0.2,
                'weather_code' => 61,
                'pressure_msl' => 1014.2,
                'wind_speed_10m' => 3.4,
                'wind_direction_10m' => 360,
                'wind_gusts_10m' => 6.1,
            ],
        ], $requestedCoordinates);

        self::assertSame($requestedCoordinates, $weather->coordinates());
        self::assertSame('2026-08-08T11:10:00+00:00', $weather->observedAt()->format('c'));
        self::assertSame(21.4, $weather->temperature()->celsius());
        self::assertSame(21.0, $weather->feelsLike()?->celsius());
        self::assertSame(67.0, $weather->humidityPercent());
        self::assertSame(1014.2, $weather->pressureHectopascals());
        self::assertSame(WeatherCondition::Rain, $weather->condition());
        $wind = $weather->wind();
        self::assertNotNull($wind);
        self::assertSame(3.4, $wind->speedMetersPerSecond());
        self::assertSame(0.0, $wind->directionDegrees());
        self::assertSame(6.1, $wind->gustMetersPerSecond());
        self::assertSame(0.2, $weather->precipitationMillimeters());
    }

    /** @param array<string, mixed> $payload */
    #[DataProvider('malformedPayloads')]
    #[TestDox('Отклоняет malformed структуру и типы текущего ответа')]
    public function testItRejectsMalformedPayload(array $payload): void
    {
        $this->expectException(MalformedResponseException::class);

        OpenMeteoCurrentWeatherMapper::map($payload, Coordinates::fromDegrees(55.7558, 37.6173));
    }

    #[TestDox('Преобразует некорректные canonical значения в ошибку provider response')]
    public function testItWrapsInvalidCanonicalValuesAsMalformedResponse(): void
    {
        $payload = self::validPayload();
        $payload['current']['relative_humidity_2m'] = 120;

        $this->expectException(MalformedResponseException::class);

        OpenMeteoCurrentWeatherMapper::map($payload, Coordinates::fromDegrees(55.7558, 37.6173));
    }

    /** @return iterable<string, array{array<string, mixed>}> */
    public static function malformedPayloads(): iterable
    {
        yield 'missing current section' => [[]];

        $missingTemperature = self::validPayload();
        unset($missingTemperature['current']['temperature_2m']);
        yield 'missing required current field' => [$missingTemperature];

        $stringPrecipitation = self::validPayload();
        $stringPrecipitation['current']['precipitation'] = '0.2';
        yield 'numeric field as string' => [$stringPrecipitation];

        $floatTime = self::validPayload();
        $floatTime['current']['time'] = 1786187400.0;
        yield 'time is not an integer' => [$floatTime];
    }

    /** @return array{current: array<string, int|float>} */
    private static function validPayload(): array
    {
        return [
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
        ];
    }
}
