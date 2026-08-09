# Getting started

## Choose a language

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](./getting-started.md) | **Selected** | [Español](./getting-started_es.md) | [中文](./getting-started_zh.md) | [Français](./getting-started_fr.md) | [Deutsch](./getting-started_de.md) |

This guide covers installation, choosing a built-in provider, creating coordinates, and making the first current-weather request.

## Requirements

You need `PHP ^8.4` and `Composer`.

## Installation

```bash
composer require yaleksandr89/weather
```

## Choosing a provider

Open-Meteo is the simplest place to start: the built-in integration does not require an API key. For either option, create `Weather` with `Weather::create()`.

### Open-Meteo

`OpenMeteoConfig` takes no parameters.

```php
use Yaleksandr\Weather\Config\OpenMeteoConfig;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(new OpenMeteoConfig());
```

### WeatherAPI

`WeatherApiConfig` requires an API key.

```php
use Yaleksandr\Weather\Config\WeatherApiConfig;
use Yaleksandr\Weather\Weather;

$weather = Weather::create(
    new WeatherApiConfig('YOUR_WEATHERAPI_KEY'),
);
```

Replace `YOUR_WEATHERAPI_KEY` with your key and do not commit a real key to the repository. Key storage is the application's responsibility; use application configuration, environment variables, or a secret store.

For a detailed comparison and links to the service documentation, see the [provider guide](providers_en.md).

## Coordinates

Create `Coordinates` from latitude and longitude in degrees. Latitude must be in `[-90, 90]`, longitude in `[-180, 180]`; both values must be finite numbers.

```php
use Yaleksandr\Weather\Value\Coordinates;

$coordinates = Coordinates::fromDegrees(55.7558, 37.6173);
```

## Getting current weather

Pass the coordinates to `current()`.

```php
$current = $weather->current($coordinates);

echo $current->temperature()->celsius();
echo $current->condition()->value;
```

For result fields and optional values, see the [`CurrentWeather` guide](../reference/current-weather_en.md). Input-data and service-request errors are described in the [error guide](../reference/errors_en.md).
