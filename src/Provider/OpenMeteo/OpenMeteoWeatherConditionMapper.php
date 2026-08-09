<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Provider\OpenMeteo;

use Yaleksandr\Weather\Value\WeatherCondition;

final class OpenMeteoWeatherConditionMapper
{
    public static function map(int $weatherCode): WeatherCondition
    {
        return match ($weatherCode) {
            0, 1 => WeatherCondition::Clear,
            2 => WeatherCondition::PartlyCloudy,
            3 => WeatherCondition::Cloudy,
            45, 48 => WeatherCondition::Fog,
            51, 53, 55 => WeatherCondition::Drizzle,
            56, 57, 66, 67 => WeatherCondition::Sleet,
            61, 63, 65, 80, 81, 82 => WeatherCondition::Rain,
            71, 73, 75, 77, 85, 86 => WeatherCondition::Snow,
            95, 96, 99 => WeatherCondition::Thunderstorm,
            default => WeatherCondition::Unknown,
        };
    }
}
