<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Tests;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Yaleksandr\Weather\Config\OpenMeteoConfig;
use Yaleksandr\Weather\Config\WeatherApiConfig;
use Yaleksandr\Weather\Contract\CurrentWeatherProvider;
use Yaleksandr\Weather\Model\CurrentWeather;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Value\Temperature;
use Yaleksandr\Weather\Value\WeatherCondition;
use Yaleksandr\Weather\Weather;

final class WeatherTest extends TestCase
{
    #[DataProvider('supportedConfigs')]
    #[TestDox('Создаёт facade для поддерживаемой конфигурации без ошибки')]
    public function testItAcceptsSupportedConfigWithoutError(WeatherApiConfig|OpenMeteoConfig $config): void
    {
        $this->expectNotToPerformAssertions();

        Weather::create($config);
    }

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

    /** @return iterable<string, array{WeatherApiConfig|OpenMeteoConfig}> */
    public static function supportedConfigs(): iterable
    {
        yield 'WeatherAPI' => [new WeatherApiConfig('test-api-key')];
        yield 'Open-Meteo' => [new OpenMeteoConfig()];
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
