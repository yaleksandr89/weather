<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Tests\Internal;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Yaleksandr\Weather\Config\OpenMeteoConfig;
use Yaleksandr\Weather\Config\WeatherApiConfig;
use Yaleksandr\Weather\Internal\ProviderFactory;
use Yaleksandr\Weather\Provider\OpenMeteo\OpenMeteoProvider;
use Yaleksandr\Weather\Provider\WeatherApi\WeatherApiProvider;

final class ProviderFactoryTest extends TestCase
{
    #[TestDox('Выбирает WeatherAPI provider для WeatherAPI config')]
    public function testItSelectsWeatherApiProvider(): void
    {
        $provider = ProviderFactory::create(new WeatherApiConfig('test-api-key'));

        self::assertInstanceOf(WeatherApiProvider::class, $provider);
    }

    #[TestDox('Выбирает Open-Meteo provider для Open-Meteo config')]
    public function testItSelectsOpenMeteoProvider(): void
    {
        $provider = ProviderFactory::create(new OpenMeteoConfig());

        self::assertInstanceOf(OpenMeteoProvider::class, $provider);
    }
}
