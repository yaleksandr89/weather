<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Tests;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Yaleksandr\Weather\Contract\CurrentWeatherProvider;
use Yaleksandr\Weather\Model\CurrentWeather;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Value\Temperature;
use Yaleksandr\Weather\Value\WeatherCondition;
use Yaleksandr\Weather\Weather;

final class WeatherTest extends TestCase
{
    #[TestDox('Принимает custom provider и делегирует ему получение текущей погоды без изменения значений')]
    public function testItAcceptsCustomProviderAndDelegatesCurrentWeather(): void
    {
        $coordinates = Coordinates::fromDegrees(55.7558, 37.6173);
        $currentWeather = CurrentWeather::fromObservation(
            $coordinates,
            new DateTimeImmutable('2026-08-08T12:00:00+03:00'),
            Temperature::fromCelsius(21.5),
            WeatherCondition::Clear,
        );
        $provider = new RecordingCurrentWeatherProvider($currentWeather);
        $weather = new Weather($provider);

        $result = $weather->current($coordinates);

        self::assertSame($coordinates, $provider->coordinates);
        self::assertSame($currentWeather, $result);
    }
}

final class RecordingCurrentWeatherProvider implements CurrentWeatherProvider
{
    public ?Coordinates $coordinates = null;

    public function __construct(
        private readonly CurrentWeather $currentWeather,
    ) {}

    public function current(Coordinates $coordinates): CurrentWeather
    {
        $this->coordinates = $coordinates;

        return $this->currentWeather;
    }
}
