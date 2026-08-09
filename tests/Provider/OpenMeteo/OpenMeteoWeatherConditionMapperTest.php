<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Tests\Provider\OpenMeteo;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Yaleksandr\Weather\Provider\OpenMeteo\OpenMeteoWeatherConditionMapper;
use Yaleksandr\Weather\Value\WeatherCondition;

final class OpenMeteoWeatherConditionMapperTest extends TestCase
{
    #[DataProvider('knownWeatherCodes')]
    #[TestDox('Сопоставляет поддерживаемый WMO weather code с погодным состоянием')]
    public function testItMapsKnownWeatherCode(int $weatherCode, WeatherCondition $expectedCondition): void
    {
        self::assertSame($expectedCondition, OpenMeteoWeatherConditionMapper::map($weatherCode));
    }

    #[TestDox('Сопоставляет неизвестный WMO weather code с неизвестным состоянием')]
    public function testItMapsUnknownWeatherCodeToUnknownCondition(): void
    {
        self::assertSame(WeatherCondition::Unknown, OpenMeteoWeatherConditionMapper::map(999));
    }

    /** @return iterable<string, array{int, WeatherCondition}> */
    public static function knownWeatherCodes(): iterable
    {
        yield 'clear sky' => [0, WeatherCondition::Clear];
        yield 'mainly clear' => [1, WeatherCondition::Clear];
        yield 'partly cloudy' => [2, WeatherCondition::PartlyCloudy];
        yield 'overcast' => [3, WeatherCondition::Cloudy];
        yield 'fog' => [45, WeatherCondition::Fog];
        yield 'depositing rime fog' => [48, WeatherCondition::Fog];
        yield 'light drizzle' => [51, WeatherCondition::Drizzle];
        yield 'moderate drizzle' => [53, WeatherCondition::Drizzle];
        yield 'dense drizzle' => [55, WeatherCondition::Drizzle];
        yield 'light freezing drizzle' => [56, WeatherCondition::Sleet];
        yield 'dense freezing drizzle' => [57, WeatherCondition::Sleet];
        yield 'slight rain' => [61, WeatherCondition::Rain];
        yield 'moderate rain' => [63, WeatherCondition::Rain];
        yield 'heavy rain' => [65, WeatherCondition::Rain];
        yield 'light freezing rain' => [66, WeatherCondition::Sleet];
        yield 'heavy freezing rain' => [67, WeatherCondition::Sleet];
        yield 'slight snowfall' => [71, WeatherCondition::Snow];
        yield 'moderate snowfall' => [73, WeatherCondition::Snow];
        yield 'heavy snowfall' => [75, WeatherCondition::Snow];
        yield 'snow grains' => [77, WeatherCondition::Snow];
        yield 'slight rain showers' => [80, WeatherCondition::Rain];
        yield 'moderate rain showers' => [81, WeatherCondition::Rain];
        yield 'violent rain showers' => [82, WeatherCondition::Rain];
        yield 'slight snow showers' => [85, WeatherCondition::Snow];
        yield 'heavy snow showers' => [86, WeatherCondition::Snow];
        yield 'thunderstorm' => [95, WeatherCondition::Thunderstorm];
        yield 'thunderstorm with slight hail' => [96, WeatherCondition::Thunderstorm];
        yield 'thunderstorm with heavy hail' => [99, WeatherCondition::Thunderstorm];
    }
}
