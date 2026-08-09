<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Provider\WeatherApi;

use Yaleksandr\Weather\Value\WeatherCondition;

final class WeatherApiWeatherConditionMapper
{
    public static function map(int $conditionCode): WeatherCondition
    {
        return match ($conditionCode) {
            1000 => WeatherCondition::Clear,
            1003 => WeatherCondition::PartlyCloudy,
            1006, 1009 => WeatherCondition::Cloudy,
            1030, 1135, 1147 => WeatherCondition::Fog,
            1150, 1153 => WeatherCondition::Drizzle,
            1063, 1180, 1183, 1186, 1189, 1192, 1195, 1240, 1243, 1246 => WeatherCondition::Rain,
            1066, 1114, 1117, 1210, 1213, 1216, 1219, 1222, 1225, 1255, 1258 => WeatherCondition::Snow,
            1069, 1072, 1168, 1171, 1198, 1201, 1204, 1207, 1237, 1249, 1252, 1261, 1264 => WeatherCondition::Sleet,
            1087, 1273, 1276, 1279, 1282 => WeatherCondition::Thunderstorm,
            default => WeatherCondition::Unknown,
        };
    }
}
