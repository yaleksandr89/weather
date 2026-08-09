<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Tests\Provider\WeatherApi;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Yaleksandr\Weather\Provider\WeatherApi\WeatherApiWeatherConditionMapper;
use Yaleksandr\Weather\Value\WeatherCondition;

final class WeatherApiWeatherConditionMapperTest extends TestCase
{
    #[DataProvider('knownConditionCodes')]
    #[TestDox('Сопоставляет поддерживаемый WeatherAPI condition code с погодным состоянием')]
    public function testItMapsKnownConditionCode(int $conditionCode, WeatherCondition $expectedCondition): void
    {
        self::assertSame($expectedCondition, WeatherApiWeatherConditionMapper::map($conditionCode));
    }

    #[TestDox('Сопоставляет неизвестный WeatherAPI condition code с неизвестным состоянием')]
    public function testItMapsUnknownConditionCodeToUnknownCondition(): void
    {
        self::assertSame(WeatherCondition::Unknown, WeatherApiWeatherConditionMapper::map(9999));
    }

    /** @return iterable<string, array{int, WeatherCondition}> */
    public static function knownConditionCodes(): iterable
    {
        yield 'clear' => [1000, WeatherCondition::Clear];
        yield 'partly cloudy' => [1003, WeatherCondition::PartlyCloudy];
        yield 'cloudy' => [1006, WeatherCondition::Cloudy];
        yield 'overcast' => [1009, WeatherCondition::Cloudy];
        yield 'mist' => [1030, WeatherCondition::Fog];
        yield 'haze' => [1012, WeatherCondition::Unknown];
        yield 'dust haze' => [1015, WeatherCondition::Unknown];
        yield 'blowing dust' => [1018, WeatherCondition::Unknown];
        yield 'dust storm' => [1021, WeatherCondition::Unknown];
        yield 'sandstorm' => [1024, WeatherCondition::Unknown];
        yield 'severe sandstorm' => [1027, WeatherCondition::Unknown];
        yield 'smoke' => [1033, WeatherCondition::Unknown];
        yield 'smoky haze' => [1036, WeatherCondition::Unknown];
        yield 'smog' => [1039, WeatherCondition::Unknown];
        yield 'severe smog' => [1042, WeatherCondition::Unknown];
        yield 'Saharan dust' => [1045, WeatherCondition::Unknown];
        yield 'dust' => [1048, WeatherCondition::Unknown];
        yield 'thundery outbreaks possible' => [1087, WeatherCondition::Thunderstorm];
        yield 'blowing snow' => [1114, WeatherCondition::Snow];
        yield 'blizzard' => [1117, WeatherCondition::Snow];
        yield 'fog' => [1135, WeatherCondition::Fog];
        yield 'freezing fog' => [1147, WeatherCondition::Fog];
        yield 'patchy light drizzle' => [1150, WeatherCondition::Drizzle];
        yield 'light drizzle' => [1153, WeatherCondition::Drizzle];
        yield 'patchy rain possible' => [1063, WeatherCondition::Rain];
        yield 'patchy snow possible' => [1066, WeatherCondition::Snow];
        yield 'patchy sleet possible' => [1069, WeatherCondition::Sleet];
        yield 'patchy freezing drizzle possible' => [1072, WeatherCondition::Sleet];
        yield 'freezing drizzle' => [1168, WeatherCondition::Sleet];
        yield 'heavy freezing drizzle' => [1171, WeatherCondition::Sleet];
        yield 'patchy light rain' => [1180, WeatherCondition::Rain];
        yield 'light rain' => [1183, WeatherCondition::Rain];
        yield 'moderate rain at times' => [1186, WeatherCondition::Rain];
        yield 'moderate rain' => [1189, WeatherCondition::Rain];
        yield 'heavy rain at times' => [1192, WeatherCondition::Rain];
        yield 'heavy rain' => [1195, WeatherCondition::Rain];
        yield 'light freezing rain' => [1198, WeatherCondition::Sleet];
        yield 'moderate or heavy freezing rain' => [1201, WeatherCondition::Sleet];
        yield 'light sleet' => [1204, WeatherCondition::Sleet];
        yield 'moderate or heavy sleet' => [1207, WeatherCondition::Sleet];
        yield 'patchy light snow' => [1210, WeatherCondition::Snow];
        yield 'light snow' => [1213, WeatherCondition::Snow];
        yield 'patchy moderate snow' => [1216, WeatherCondition::Snow];
        yield 'moderate snow' => [1219, WeatherCondition::Snow];
        yield 'patchy heavy snow' => [1222, WeatherCondition::Snow];
        yield 'heavy snow' => [1225, WeatherCondition::Snow];
        yield 'ice pellets' => [1237, WeatherCondition::Sleet];
        yield 'light rain shower' => [1240, WeatherCondition::Rain];
        yield 'moderate or heavy rain shower' => [1243, WeatherCondition::Rain];
        yield 'torrential rain shower' => [1246, WeatherCondition::Rain];
        yield 'light sleet showers' => [1249, WeatherCondition::Sleet];
        yield 'moderate or heavy sleet showers' => [1252, WeatherCondition::Sleet];
        yield 'light snow showers' => [1255, WeatherCondition::Snow];
        yield 'moderate or heavy snow showers' => [1258, WeatherCondition::Snow];
        yield 'light showers of ice pellets' => [1261, WeatherCondition::Sleet];
        yield 'moderate or heavy showers of ice pellets' => [1264, WeatherCondition::Sleet];
        yield 'patchy light rain with thunder' => [1273, WeatherCondition::Thunderstorm];
        yield 'moderate or heavy rain with thunder' => [1276, WeatherCondition::Thunderstorm];
        yield 'patchy light snow with thunder' => [1279, WeatherCondition::Thunderstorm];
        yield 'moderate or heavy snow with thunder' => [1282, WeatherCondition::Thunderstorm];
    }
}
