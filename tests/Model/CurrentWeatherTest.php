<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Tests\Model;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Yaleksandr\Weather\Exception\InvalidCurrentWeatherException;
use Yaleksandr\Weather\Model\CurrentWeather;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Value\Temperature;
use Yaleksandr\Weather\Value\WeatherCondition;
use Yaleksandr\Weather\Value\Wind;

final class CurrentWeatherTest extends TestCase
{
    #[TestDox('Сохраняет полное наблюдение без изменения значений')]
    public function testItPreservesCompleteObservation(): void
    {
        $coordinates = Coordinates::fromDegrees(55.7558, 37.6173);
        $observedAt = new DateTimeImmutable('2026-08-08T12:00:00+03:00');
        $temperature = Temperature::fromCelsius(21.5);
        $condition = WeatherCondition::PartlyCloudy;
        $feelsLike = Temperature::fromCelsius(20.0);
        $wind = Wind::fromMetersPerSecond(4.5, 270.0, 7.25);

        $weather = CurrentWeather::fromObservation(
            $coordinates,
            $observedAt,
            $temperature,
            $condition,
            $feelsLike,
            67.5,
            1013.25,
            $wind,
            1.75,
        );

        self::assertSame($coordinates, $weather->coordinates());
        self::assertSame($observedAt, $weather->observedAt());
        self::assertSame($temperature, $weather->temperature());
        self::assertSame($condition, $weather->condition());
        self::assertSame($feelsLike, $weather->feelsLike());
        self::assertSame(67.5, $weather->humidityPercent());
        self::assertSame(1013.25, $weather->pressureHectopascals());
        self::assertSame($wind, $weather->wind());
        self::assertSame(1.75, $weather->precipitationMillimeters());
    }

    #[TestDox('Допускает отсутствующие необязательные поля наблюдения')]
    public function testItAllowsOmittedOptionalFields(): void
    {
        $weather = CurrentWeather::fromObservation(
            Coordinates::fromDegrees(55.7558, 37.6173),
            new DateTimeImmutable('2026-08-08T12:00:00+03:00'),
            Temperature::fromCelsius(21.5),
            WeatherCondition::Clear,
        );

        self::assertNull($weather->feelsLike());
        self::assertNull($weather->humidityPercent());
        self::assertNull($weather->pressureHectopascals());
        self::assertNull($weather->wind());
        self::assertNull($weather->precipitationMillimeters());
    }

    #[DataProvider('invalidHumidityValues')]
    #[TestDox('Отклоняет некорректную влажность')]
    public function testItRejectsInvalidHumidity(float $humidityPercent): void
    {
        $this->expectException(InvalidCurrentWeatherException::class);

        CurrentWeather::fromObservation(
            Coordinates::fromDegrees(0.0, 0.0),
            new DateTimeImmutable('2026-08-08T12:00:00+03:00'),
            Temperature::fromCelsius(0.0),
            WeatherCondition::Clear,
            humidityPercent: $humidityPercent,
        );
    }

    #[DataProvider('invalidPressureValues')]
    #[TestDox('Отклоняет некорректное давление')]
    public function testItRejectsInvalidPressure(float $pressureHectopascals): void
    {
        $this->expectException(InvalidCurrentWeatherException::class);

        CurrentWeather::fromObservation(
            Coordinates::fromDegrees(0.0, 0.0),
            new DateTimeImmutable('2026-08-08T12:00:00+03:00'),
            Temperature::fromCelsius(0.0),
            WeatherCondition::Clear,
            pressureHectopascals: $pressureHectopascals,
        );
    }

    #[DataProvider('invalidPrecipitationValues')]
    #[TestDox('Отклоняет некорректные осадки')]
    public function testItRejectsInvalidPrecipitation(float $precipitationMillimeters): void
    {
        $this->expectException(InvalidCurrentWeatherException::class);

        CurrentWeather::fromObservation(
            Coordinates::fromDegrees(0.0, 0.0),
            new DateTimeImmutable('2026-08-08T12:00:00+03:00'),
            Temperature::fromCelsius(0.0),
            WeatherCondition::Clear,
            precipitationMillimeters: $precipitationMillimeters,
        );
    }

    /** @return iterable<string, array{float}> */
    public static function invalidHumidityValues(): iterable
    {
        yield 'negative' => [-0.1];
        yield 'above maximum' => [100.1];
        yield 'positive infinity' => [INF];
        yield 'negative infinity' => [-INF];
        yield 'not a number' => [NAN];
    }

    /** @return iterable<string, array{float}> */
    public static function invalidPressureValues(): iterable
    {
        yield 'zero' => [0.0];
        yield 'negative' => [-0.1];
        yield 'positive infinity' => [INF];
        yield 'negative infinity' => [-INF];
        yield 'not a number' => [NAN];
    }

    /** @return iterable<string, array{float}> */
    public static function invalidPrecipitationValues(): iterable
    {
        yield 'negative' => [-0.1];
        yield 'positive infinity' => [INF];
        yield 'negative infinity' => [-INF];
        yield 'not a number' => [NAN];
    }
}
