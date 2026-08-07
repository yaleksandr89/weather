<?php

declare(strict_types=1);

namespace Yaleksandr\Weather\Value;

enum WeatherCondition: string
{
    case Clear = 'clear';
    case PartlyCloudy = 'partly_cloudy';
    case Cloudy = 'cloudy';
    case Fog = 'fog';
    case Drizzle = 'drizzle';
    case Rain = 'rain';
    case Snow = 'snow';
    case Sleet = 'sleet';
    case Thunderstorm = 'thunderstorm';
    case Unknown = 'unknown';
}
