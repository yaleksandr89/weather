# Weather

[![Source Code](https://img.shields.io/badge/source-yaleksandr89%2Fweather-blue.svg?style=flat-square)](https://github.com/yaleksandr89/weather)
[![CI](https://github.com/yaleksandr89/weather/actions/workflows/ci.yml/badge.svg)](https://github.com/yaleksandr89/weather/actions/workflows/ci.yml)
[![Latest Stable Version](https://img.shields.io/packagist/v/yaleksandr89/weather.svg?style=flat-square)](https://packagist.org/packages/yaleksandr89/weather)
[![Total Downloads](https://img.shields.io/packagist/dt/yaleksandr89/weather.svg?style=flat-square)](https://packagist.org/packages/yaleksandr89/weather)
[![PHP](https://img.shields.io/badge/PHP-8.4%2B-777BB4.svg?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![guzzlehttp/guzzle](https://img.shields.io/badge/guzzlehttp%2Fguzzle-%5E8.0.1-4E5D94.svg?style=flat-square)](https://packagist.org/packages/guzzlehttp/guzzle)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](../../LICENSE)

<p align="center">
  <img
    src="../assets/weather-readme-cover.png"
    alt="Weather — current weather PHP package with unified provider normalization"
    width="100%"
  >
</p>

## Choose a language

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../README.md) | **Selected** | [Español](./README_es.md) | [中文](./README_zh.md) | [Français](./README_fr.md) | [Deutsch](./README_de.md) |

A library for retrieving current weather by coordinates through WeatherAPI and Open-Meteo.
Data from different services is normalized into one typed format and common units.

## Why use this package

WeatherAPI and Open-Meteo expose different HTTP APIs and response formats. Weather provides one PHP API for retrieving current weather by coordinates, so application code does not need to account for service-specific details.

## What the package does

- retrieves current weather by latitude and longitude;
- supports WeatherAPI and Open-Meteo;
- returns the result as `CurrentWeather`;
- normalizes weather data to common metric units;
- provides typed weather conditions through `WeatherCondition`;
- supports custom data sources through `CurrentWeatherProvider`.

## Requirements

- `PHP ^8.4`;
- `Composer`.

## Quick start

Install the package:

```bash
composer require yaleksandr89/weather
```

### Open-Meteo

Open-Meteo does not require an API key.

```php
use Yaleksandr\Weather\Config\OpenMeteoConfig;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(new OpenMeteoConfig());

$current = $weather->current(
    Coordinates::fromDegrees(55.7558, 37.6173),
);

echo $current->temperature()->celsius();
echo $current->condition()->value;
```

<details>
<summary>Show the real Open-Meteo response and CurrentWeather</summary>

#### Real Open-Meteo response fragment

```json
{
    "time": 1786285800,
    "interval": 900,
    "temperature_2m": 21.8,
    "relative_humidity_2m": 48,
    "apparent_temperature": 20.4,
    "precipitation": 0.0,
    "weather_code": 1,
    "pressure_msl": 1018.4,
    "wind_speed_10m": 2.91,
    "wind_direction_10m": 333,
    "wind_gusts_10m": 8.1
}
```

#### Result after normalization

```text
Yaleksandr\Weather\Model\CurrentWeather {
    coordinates: Yaleksandr\Weather\Value\Coordinates {
        latitude: 55.7558
        longitude: 37.6173
    }
    observedAt: DateTimeImmutable {
        date: 2026-08-09 14:30:00 UTC
    }
    temperature: Yaleksandr\Weather\Value\Temperature {
        celsius: 21.8
    }
    condition: Yaleksandr\Weather\Value\WeatherCondition {
        name: Clear
        value: clear
    }
    feelsLike: Yaleksandr\Weather\Value\Temperature {
        celsius: 20.4
    }
    humidityPercent: 48.0
    pressureHectopascals: 1018.4
    wind: Yaleksandr\Weather\Value\Wind {
        speed: 2.91
        directionDegrees: 333.0
        gust: 8.1
    }
    precipitationMillimeters: 0.0
}
```

</details>

### WeatherAPI

WeatherAPI requires an API key:

```php
use Yaleksandr\Weather\Config\WeatherApiConfig;
use Yaleksandr\Weather\Value\Coordinates;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(
    new WeatherApiConfig('YOUR_WEATHERAPI_KEY'),
);

$current = $weather->current(
    Coordinates::fromDegrees(55.7558, 37.6173),
);
```

Replace `YOUR_WEATHERAPI_KEY` with your key. Do not commit a real key to the repository; keep it outside source code, for example in application configuration, environment variables, or a secret store.

<details>
<summary>Show the real WeatherAPI response and CurrentWeather</summary>

#### Real WeatherAPI response fragment

```json
{
    "last_updated_epoch": 1786290300,
    "temp_c": 21.4,
    "condition": {
        "text": "Sunny",
        "code": 1000
    },
    "wind_kph": 12.6,
    "wind_degree": 341,
    "pressure_mb": 1019.0,
    "precip_mm": 0.0,
    "humidity": 41,
    "feelslike_c": 18.4,
    "gust_kph": 15.8
}
```

#### Result after normalization

```text
Yaleksandr\Weather\Model\CurrentWeather {
    coordinates: Yaleksandr\Weather\Value\Coordinates {
        latitude: 55.7558
        longitude: 37.6173
    }
    observedAt: DateTimeImmutable {
        date: 2026-08-09 15:45:00 UTC
    }
    temperature: Yaleksandr\Weather\Value\Temperature {
        celsius: 21.4
    }
    condition: Yaleksandr\Weather\Value\WeatherCondition {
        name: Clear
        value: clear
    }
    feelsLike: Yaleksandr\Weather\Value\Temperature {
        celsius: 18.4
    }
    humidityPercent: 41.0
    pressureHectopascals: 1019.0
    wind: Yaleksandr\Weather\Value\Wind {
        speed: 3.5
        directionDegrees: 341.0
        gust: 4.3888888888889
    }
    precipitationMillimeters: 0.0
}
```

</details>

Both providers return `CurrentWeather`. The complete data model, units, optional values, and usage examples are documented in the [`CurrentWeather` guide](../reference/current-weather_en.md). Built-in service configuration and provider differences are covered in the [provider guide](../guides/providers_en.md).

## Error handling

Input errors are kept separate from errors that occur while calling a weather service.

Handling rules and the exception hierarchy are documented in the [error guide](../reference/errors_en.md).

## Custom provider

If WeatherAPI and Open-Meteo do not fit your needs, implement `CurrentWeatherProvider` and pass it directly:

```php
use Yaleksandr\Weather\Weather;

$weather = new Weather($customProvider);
```

The contract and implementation example are available in the [custom provider guide](../guides/custom-provider_en.md).

## Limitations

- current weather only;
- coordinates must be known in advance;
- no city search or geocoding;
- no automatic provider fallback;
- no automatic retry or cache.

## Feedback

- reproducible bugs — [GitHub Issues](https://github.com/yaleksandr89/weather/issues).

---

<p align="center">
  If the package is useful, give it a star on GitHub — it helps other developers discover it. 🤘
</p>
